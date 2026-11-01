<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TenantIdPhotoController extends Controller
{
    public function preview(Tenant $tenant): StreamedResponse
    {
        abort_unless($tenant->id_photo_path && Storage::disk('local')->exists($tenant->id_photo_path), 404);

        $disk = Storage::disk('local');
        $stream = $disk->readStream($tenant->id_photo_path);
        abort_if($stream === false, 500, 'Foto KTP tidak dapat dibaca.');

        return response()->stream(function () use ($stream): void {
            fpassthru($stream);
            fclose($stream);
        }, Response::HTTP_OK, [
            'Content-Type' => $disk->mimeType($tenant->id_photo_path) ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="ktp-'.$tenant->id.'.'.pathinfo($tenant->id_photo_path, PATHINFO_EXTENSION).'"',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function show(Tenant $tenant): StreamedResponse
    {
        abort_unless($tenant->id_photo_path && Storage::disk('local')->exists($tenant->id_photo_path), 404);

        return Storage::disk('local')->download($tenant->id_photo_path, 'ktp-'.$tenant->id.'.'.pathinfo($tenant->id_photo_path, PATHINFO_EXTENSION));
    }
}
