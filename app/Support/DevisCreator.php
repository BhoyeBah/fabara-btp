<?php

namespace App\Support;

use App\Models\DevisRequest;
use App\Notifications\NewDevisReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Throwable;

/** Enregistre une demande de devis (avec ses photos) et prévient l'admin. */
class DevisCreator
{
    public static function create(Request $request, array $data): DevisRequest
    {
        $devis = DevisRequest::create([
            'user_id' => $request->user()?->id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'commune' => $data['commune'],
            'property_type' => $data['property_type'],
            'works' => array_values($data['works']),
            'surface' => $data['surface'] ?? null,
            'start_when' => $data['start_when'] ?? null,
            'message' => $data['message'] ?? null,
            'consent_at' => now(),
        ]);

        foreach ($request->file('photos', []) as $file) {
            // Disque privé : storage/app/private, jamais accessible directement par une URL.
            $path = $file->store('devis/'.$devis->id, 'local');
            $devis->photos()->create([
                'path' => $path,
                'original_name' => mb_substr($file->getClientOriginalName(), 0, 190),
                'size' => $file->getSize(),
            ]);
        }

        if ($to = config('fabara.admin_notify_email')) {
            try {
                Notification::route('mail', $to)->notify(new NewDevisReceived($devis));
            } catch (Throwable $e) {
                report($e); // un e-mail en échec ne doit pas bloquer la demande du client
            }
        }

        return $devis;
    }
}
