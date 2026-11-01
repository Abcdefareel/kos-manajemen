<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MvpWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function room(array $attributes = []): Room
    {
        return Room::create($attributes + [
            'room_number' => '01',
            'type' => 'Standard',
            'price_per_month' => 750000,
            'status' => Room::STATUS_EMPTY,
        ]);
    }

    private function tenant(array $attributes = []): Tenant
    {
        return Tenant::create($attributes + [
            'name' => 'Budi Santoso',
            'phone' => '08123456789',
            'id_number' => '1234567890123456',
        ]);
    }

    public function test_private_pages_require_login_and_login_works(): void
    {
        $this->get(route('rooms.index'))->assertRedirect(route('login'));
        $user = $this->admin();

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('rooms.index'));
        $this->get(route('rooms.index'))->assertOk();
    }

    public function test_other_roles_are_denied_and_logout_ends_the_session(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'tenant']))
            ->get(route('rooms.index'))
            ->assertForbidden();

        $this->actingAs($this->admin())
            ->post(route('logout'))
            ->assertRedirect(route('login'));
        $this->get(route('rooms.index'))->assertRedirect(route('login'));
    }

    public function test_room_crud_does_not_allow_status_updates_and_restricts_deletion(): void
    {
        $this->actingAs($this->admin());
        $room = $this->room();

        $this->put(route('rooms.update', $room), [
            'room_number' => '01',
            'type' => 'Deluxe',
            'price_per_month' => 900000,
            'status' => Room::STATUS_EMPTY,
        ])->assertRedirect(route('rooms.index'));
        $this->assertSame(Room::STATUS_EMPTY, $room->fresh()->status);

        $this->post(route('bookings.store'), [
            'room_id' => $room->id,
            'tenant_id' => $this->tenant()->id,
            'start_date' => '2026-12-01',
            'duration_months' => 3,
            'down_payment' => 100000,
        ])->assertRedirect(route('bookings.index'));

        $this->delete(route('rooms.destroy', $room))->assertSessionHas('error');
        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
    }

    public function test_booking_lifecycle_updates_room_status_transactionally_and_prevents_double_booking(): void
    {
        $this->actingAs($this->admin());
        $room = $this->room();
        $tenant = $this->tenant();
        $payload = [
            'room_id' => $room->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-12-01',
            'duration_months' => 3,
            'down_payment' => 250000,
        ];

        $this->post(route('bookings.store'), $payload)->assertRedirect(route('bookings.index'));
        $booking = Booking::firstOrFail();
        $this->assertSame(Room::STATUS_BOOKED, $room->fresh()->status);

        $this->from(route('bookings.create'))->post(route('bookings.store'), $payload)
            ->assertSessionHasErrors('room_id');
        $this->assertSame(1, Booking::count());

        $otherRoom = $this->room(['room_number' => '02']);
        $this->put(route('bookings.update', $booking), array_replace($payload, ['room_id' => $otherRoom->id]))
            ->assertRedirect(route('bookings.index'));
        $this->assertSame(Room::STATUS_EMPTY, $room->fresh()->status);
        $this->assertSame(Room::STATUS_BOOKED, $otherRoom->fresh()->status);

        $this->patch(route('bookings.cancel', $booking))->assertRedirect(route('bookings.index'));
        $this->assertSame(Booking::STATUS_CANCELLED, $booking->fresh()->status);
        $this->assertSame(Room::STATUS_EMPTY, $otherRoom->fresh()->status);
    }

    public function test_booking_search_and_filters_work(): void
    {
        $this->actingAs($this->admin());
        $room = $this->room();
        $tenant = $this->tenant();
        Booking::create([
            'room_id' => $room->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-12-01',
            'duration_months' => 2,
            'down_payment' => 50000,
            'status' => Booking::STATUS_BOOKED,
        ]);

        $this->get(route('bookings.index', ['search' => 'budi', 'status' => 'dipesan', 'date_from' => '2026-11-01']))
            ->assertOk()->assertSee('Budi Santoso');
        $this->get(route('bookings.index', ['search' => 'not-found']))->assertOk()->assertSee('Belum ada booking');
    }

    public function test_tenant_photo_is_stored_privately_and_requires_login(): void
    {
        Storage::fake('local');
        $this->actingAs($this->admin());

        $this->post(route('tenants.store'), [
            'name' => 'Siti Aminah',
            'phone' => '0811111111',
            'id_number' => '9876543210123456',
            'id_photo' => UploadedFile::fake()->create('ktp.jpg', 10, 'image/jpeg'),
        ])->assertRedirect(route('tenants.index'));

        $tenant = Tenant::firstOrFail();
        Storage::disk('local')->assertExists($tenant->id_photo_path);
        $this->get(route('tenants.id-photo.preview', $tenant))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'inline; filename="ktp-'.$tenant->id.'.jpg"')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get(route('tenants.id-photo', $tenant))->assertOk();

        auth()->logout();
        $this->get(route('tenants.id-photo.preview', $tenant))->assertRedirect(route('login'));
        $this->get(route('tenants.id-photo', $tenant))->assertRedirect(route('login'));
    }

    public function test_tenant_exit_date_can_be_set_without_an_entry_date(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('tenants.store'), [
            'name' => 'Siti Aminah',
            'phone' => '0811111111',
            'id_number' => '9876543210123456',
            'exit_date' => '2026-12-01',
        ])->assertRedirect(route('tenants.index'));

        $tenant = Tenant::where('id_number', '9876543210123456')->firstOrFail();
        $this->assertNull($tenant->entry_date);
        $this->assertSame('2026-12-01', $tenant->exit_date->format('Y-m-d'));
    }

    public function test_database_seeder_is_repeatable_and_creates_expected_accounts_and_rooms(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('rooms', 20);
        $this->assertDatabaseHas('users', ['role' => 'superadmin']);
        $this->assertDatabaseHas('users', ['role' => 'admin']);
    }
}
