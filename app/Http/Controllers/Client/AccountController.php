<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function edit(Request $request)
    {
        return view('client.account', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9 +().-]{8,30}$/'],
            'marketing' => ['nullable', 'boolean'],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => Str::lower($data['email']),
            'phone' => $data['phone'],
            'marketing_opt_in' => $request->boolean('marketing'),
        ])->save();

        return back()->with('success', 'Vos informations sont à jour.');
    }

    public function password(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->forceFill(['password' => $data['password']])->save();

        return back()->with('success', 'Mot de passe modifié.');
    }

    /**
     * Droit d'accès / portabilité : le client télécharge toutes ses données
     * dans un fichier lisible (JSON).
     */
    public function export(Request $request)
    {
        $user = $request->user()->load(['devisRequests.photos', 'devisRequests.messages']);

        $payload = [
            'exporte_le' => now()->toIso8601String(),
            'entreprise' => config('fabara.company.name'),
            'compte' => [
                'nom' => $user->name,
                'email' => $user->email,
                'telephone' => $user->phone,
                'inscrit_le' => $user->created_at?->toIso8601String(),
                'conditions_acceptees_le' => $user->terms_accepted_at?->toIso8601String(),
                'version_politique' => $user->privacy_version,
                'accepte_informations_commerciales' => $user->marketing_opt_in,
                'derniere_connexion' => $user->last_login_at?->toIso8601String(),
            ],
            'demandes' => $user->devisRequests->map(fn ($d) => [
                'reference' => $d->reference,
                'date' => $d->created_at->toIso8601String(),
                'nom' => $d->name,
                'telephone' => $d->phone,
                'email' => $d->email,
                'lieu' => $d->commune,
                'type_de_bien' => $d->property_type,
                'travaux' => $d->works,
                'surface_m2' => $d->surface,
                'demarrage' => $d->start_when,
                'description' => $d->message,
                'statut' => $d->statusLabel(),
                'montant_devis_gnf' => $d->quoted_amount,
                'photos' => $d->photos->pluck('original_name'),
                'messages' => $d->messages->map(fn ($m) => [
                    'date' => $m->created_at->toIso8601String(),
                    'auteur' => $m->from_admin ? 'FABARA' : 'Vous',
                    'texte' => $m->body,
                ]),
            ]),
        ];

        return response()->streamDownload(
            fn () => print (json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)),
            'mes-donnees-fabara.json',
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    /**
     * Suppression définitive du compte et de toutes les données associées
     * (exigence Google Play / App Store et droit à l'effacement).
     */
    public function destroy(Request $request)
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $user = $request->user();

        // Suppression via Eloquent pour effacer aussi les photos sur le disque.
        foreach ($user->devisRequests()->with('photos')->get() as $devis) {
            $devis->delete();
        }

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        DB::table('sessions')->where('user_id', $user->id)->delete();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Votre compte et toutes vos données ont été supprimés définitivement.');
    }
}
