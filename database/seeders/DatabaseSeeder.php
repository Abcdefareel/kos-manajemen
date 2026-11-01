<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'superadmin' => [
                'name' => 'Superadmin',
                'email' => env('SUPERADMIN_EMAIL') ?: (app()->environment('local', 'testing') ? 'superadmin@example.test' : null),
                'password' => env('SUPERADMIN_PASSWORD') ?: (app()->environment('local', 'testing') ? 'GantiPasswordSuperadmin123!' : null),
            ],
            'admin' => [
                'name' => 'Admin Kos',
                'email' => env('ADMIN_EMAIL') ?: (app()->environment('local', 'testing') ? 'admin@example.test' : null),
                'password' => env('ADMIN_PASSWORD') ?: (app()->environment('local', 'testing') ? 'GantiPasswordAdmin123!' : null),
            ],
        ] as $role => $account) {
            if (! $account['email'] || ! $account['password']) {
                throw new RuntimeException("Set email dan password untuk akun {$role} di environment.");
            }

            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'role' => $role,
                    'password' => Hash::make($account['password']),
                ],
            );
        }

        foreach (range(1, 20) as $number) {
            Room::firstOrCreate(
                ['room_number' => sprintf('%02d', $number)],
                [
                    'type' => $number <= 10 ? 'Standard' : 'Deluxe',
                    'price_per_month' => $number <= 10 ? 750000 : 1000000,
                    'facilities' => $number <= 10 ? 'Kasur, lemari, kamar mandi luar' : 'Kasur, lemari, kamar mandi dalam',
                    'status' => Room::STATUS_EMPTY,
                ],
            );
        }
    }
}
