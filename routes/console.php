<?php

use App\Models\DeletionRequest;
use App\Models\DevisRequest;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Validator;

/*
| Créer (ou promouvoir) le compte administrateur.
|   php artisan fabara:create-admin
| C'est la SEULE façon de créer un admin : aucun formulaire du site ne le permet.
*/
Artisan::command('fabara:create-admin', function () {
    $name = $this->ask('Nom complet de l\'administrateur', 'Administrateur FABARA');
    $email = strtolower(trim((string) $this->ask('Adresse e-mail de connexion')));
    $password = $this->secret('Mot de passe (8 caractères minimum, lettres et chiffres)');

    $v = Validator::make(
        ['email' => $email, 'password' => $password],
        ['email' => 'required|email', 'password' => ['required', 'min:8', 'regex:/[A-Za-z]/', 'regex:/\d/']]
    );
    if ($v->fails()) {
        foreach ($v->errors()->all() as $e) {
            $this->error($e);
        }

        return 1;
    }

    $user = User::firstOrNew(['email' => $email]);
    $user->name = $name;
    $user->password = $password;
    $user->role = User::ROLE_ADMIN;
    $user->email_verified_at ??= now();
    $user->terms_accepted_at ??= now();
    $user->save();

    $this->info("Administrateur prêt : {$email}");
    $this->line('Connexion : '.url('/connexion').'  →  redirection automatique vers /'.config('fabara.admin_path'));

    return 0;
})->purpose('Créer ou promouvoir le compte administrateur FABARA');

/*
| Purge automatique selon les durées de conservation annoncées
| dans la politique de confidentialité.
*/
Artisan::command('fabara:purge-data', function () {
    $months = (int) config('fabara.retention.guest_devis_months');
    $old = DevisRequest::whereNull('user_id')
        ->whereIn('status', ['termine', 'perdu'])
        ->where('updated_at', '<', now()->subMonths($months))
        ->get();
    foreach ($old as $devis) {
        $devis->delete(); // efface aussi les photos
    }

    $days = (int) config('fabara.retention.deletion_requests_days');
    $del = DeletionRequest::where('status', 'traitee')
        ->where('processed_at', '<', now()->subDays($days))
        ->delete();

    $years = (int) config('fabara.retention.inactive_accounts_years');
    $inactive = User::where('role', User::ROLE_CLIENT)
        ->where(fn ($q) => $q->where('last_login_at', '<', now()->subYears($years))
            ->orWhere(fn ($q2) => $q2->whereNull('last_login_at')->where('created_at', '<', now()->subYears($years))))
        ->get();
    foreach ($inactive as $user) {
        foreach ($user->devisRequests()->with('photos')->get() as $devis) {
            $devis->delete();
        }
        $user->delete();
    }

    $this->info("Demandes sans compte supprimées : {$old->count()} · demandes de suppression effacées : {$del} · comptes inactifs supprimés : {$inactive->count()}");
})->purpose('Supprimer les données arrivées au terme de leur durée de conservation');

Schedule::command('fabara:purge-data')->dailyAt('03:00');
