<?php

namespace App\Http\Controllers;

use App\Models\DevisPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function show(Request $request, DevisPhoto $photo)
    {
        $user = $request->user();
        $owner = $photo->devisRequest?->user_id;

        // 404 plutôt que 403 : on ne confirme pas l'existence du fichier.
        abort_unless($user->isAdmin() || ($owner && $owner === $user->id), 404);
        abort_unless(Storage::disk('local')->exists($photo->path), 404);

        return response()->file(Storage::disk('local')->path($photo->path), [
            'Cache-Control' => 'private, max-age=600',
        ]);
    }
}
