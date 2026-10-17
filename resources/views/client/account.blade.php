@extends('layouts.client')

@section('title', 'Mon compte')

@section('page')
<div class="page-title">
    <div>
        <h1>Mon compte et mes données</h1>
        <p>Modifiez vos informations, téléchargez vos données ou supprimez votre compte.</p>
    </div>
</div>

<div class="cols">
    <div style="display:grid;gap:22px">
        <form class="panel" method="POST" action="{{ route('client.account.update') }}">
            @csrf @method('PUT')
            <h2>Mes informations</h2>
            <label class="f" for="name">Nom complet<input id="name" name="name" required maxlength="120" value="{{ old('name', $user->name) }}"></label>
            <div class="grid2">
                <label class="f" for="email">E-mail<input id="email" name="email" type="email" required maxlength="190" value="{{ old('email', $user->email) }}"></label>
                <label class="f" for="phone">Téléphone<input id="phone" name="phone" type="tel" required maxlength="30" value="{{ old('phone', $user->phone) }}"></label>
            </div>
            <label class="check" for="marketing"><input id="marketing" type="checkbox" name="marketing" value="1" @checked(old('marketing', $user->marketing_opt_in))><span>Recevoir les offres de FABARA</span></label>
            <button class="btn btn-gold" type="submit" style="justify-self:start">Enregistrer</button>
        </form>

        <form class="panel" method="POST" action="{{ route('client.account.password') }}">
            @csrf @method('PUT')
            <h2>Mot de passe</h2>
            <label class="f" for="current_password">Mot de passe actuel<input id="current_password" name="current_password" type="password" autocomplete="current-password" required></label>
            <div class="grid2">
                <label class="f" for="password">Nouveau mot de passe<input id="password" name="password" type="password" autocomplete="new-password" required minlength="8"></label>
                <label class="f" for="password_confirmation">Confirmation<input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8"></label>
            </div>
            <button class="btn btn-quiet" type="submit" style="justify-self:start">Changer le mot de passe</button>
        </form>
    </div>

    <div style="display:grid;gap:22px">
        <div class="panel">
            <h2>Mes données</h2>
            <p class="muted">Téléchargez une copie de toutes les informations que FABARA conserve sur vous : compte, demandes et messages.</p>
            <dl class="dl">
                <dt>Compte créé</dt><dd>{{ $user->created_at->translatedFormat('d F Y') }}</dd>
                <dt>Conditions acceptées</dt><dd>{{ $user->terms_accepted_at?->translatedFormat('d F Y') ?? '—' }}</dd>
            </dl>
            <a class="btn btn-quiet" href="{{ route('client.account.export') }}" style="justify-self:start">Télécharger mes données</a>
            <p class="muted" style="font-size:14px">Voir aussi la <a href="{{ route('privacy') }}">politique de confidentialité</a>.</p>
        </div>

        <form class="danger-zone" method="POST" action="{{ route('client.account.destroy') }}" data-confirm="Supprimer définitivement votre compte et toutes vos données ? Cette action est irréversible.">
            @csrf @method('DELETE')
            <h2>Supprimer mon compte</h2>
            <p>Votre compte, vos demandes, vos photos et vos messages seront effacés définitivement. Cette action ne peut pas être annulée.</p>
            <label class="f" for="del-password">Confirmez avec votre mot de passe<input id="del-password" name="password" type="password" autocomplete="current-password" required></label>
            <button class="btn btn-danger" type="submit" style="justify-self:start">Supprimer définitivement</button>
        </form>
    </div>
</div>
@endsection
