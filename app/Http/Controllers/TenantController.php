<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);
        $search = trim((string) ($filters['search'] ?? ''));

        return view('tenants.index', [
            'tenants' => Tenant::query()
                ->when($search !== '', fn ($query) => $query->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($search).'%']))
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),
            'search' => $search,
            'tenantCount' => Tenant::count(),
        ]);
    }

    public function create(): View
    {
        return view('tenants.form', ['tenant' => new Tenant()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('id_photo')) {
            $data['id_photo_path'] = $request->file('id_photo')->store('tenant-ids', 'local');
        }

        Tenant::create($data);

        return redirect()->route('tenants.index')->with('success', 'Data penghuni berhasil ditambahkan.');
    }

    public function edit(Tenant $tenant): View
    {
        return view('tenants.form', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $this->validatedData($request, $tenant);
        $oldPhoto = $tenant->id_photo_path;

        if ($request->hasFile('id_photo')) {
            $data['id_photo_path'] = $request->file('id_photo')->store('tenant-ids', 'local');
        }

        $tenant->update($data);

        if (isset($data['id_photo_path']) && $oldPhoto) {
            Storage::disk('local')->delete($oldPhoto);
        }

        return redirect()->route('tenants.index')->with('success', 'Data penghuni berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        if ($tenant->bookings()->exists()) {
            return back()->with('error', 'Penghuni tidak dapat dihapus karena memiliki riwayat booking.');
        }

        $photo = $tenant->id_photo_path;
        $tenant->delete();
        if ($photo) {
            Storage::disk('local')->delete($photo);
        }

        return redirect()->route('tenants.index')->with('success', 'Data penghuni berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Tenant $tenant = null): array
    {
        $exitDateRules = ['nullable', 'date'];
        if ($request->filled('entry_date')) {
            $exitDateRules[] = 'after_or_equal:entry_date';
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'id_number' => ['required', 'string', 'max:100', Rule::unique('tenants', 'id_number')->ignore($tenant)],
            'id_photo' => ['nullable', 'image', 'max:5120'],
            'entry_date' => ['nullable', 'date'],
            'exit_date' => $exitDateRules,
        ]);
    }
}
