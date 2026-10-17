@extends('layouts.auth')

@section('title', 'Inscription')

@section('form')
    <div>
        <h1>Inscription</h1>
        <p class="sub">Créez votre compte client gratuit en une minute.</p>
        @if (!empty($suite))<div class="flash flash-ok" style="margin-top:12px">Pour demander un devis, connectez-vous ou créez votre compte. Vous arriverez ensuite directement sur le formulaire.</div>@endif
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label class="f" for="name">Nom complet
            <input id="name" name="name" autocomplete="name" required maxlength="120" value="{{ old('name') }}">
        </label>
        <label class="f" for="email">E-mail
            <input id="email" name="email" type="email" autocomplete="email" required maxlength="190" value="{{ old('email') }}">
        </label>
        <label class="f" for="phone">Téléphone / WhatsApp
            <input id="phone" name="phone" type="tel" autocomplete="tel" required maxlength="30" value="{{ old('phone') }}" placeholder="+224 6xx xx xx xx">
        </label>
        <div class="grid2">
            <label class="f" for="password">Mot de passe
                <input id="password" name="password" type="password" autocomplete="new-password" required minlength="8">
            </label>
            <label class="f" for="password_confirmation">Confirmation
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8">
            </label>
        </div>
        <small class="muted">8 caractères minimum, avec au moins une lettre et un chiffre.</small>
        <label class="check" for="terms">
            <input id="terms" type="checkbox" name="terms" value="1" required @checked(old('terms'))>
            <span>J'ai lu et j'accepte les <a href="{{ route('terms') }}" target="_blank">conditions d'utilisation</a> et la <a href="{{ route('privacy') }}" target="_blank">politique de confidentialité</a>.</span>
        </label>
        <label class="check" for="marketing">
            <input id="marketing" type="checkbox" name="marketing" value="1" @checked(old('marketing'))>
            <span>J'accepte de recevoir de temps en temps les offres de FABARA (facultatif, vous pouvez changer d'avis à tout moment).</span>
        </label>
        <button class="btn btn-gold btn-block" type="submit">Créer mon compte</button>
    </form>
    <p class="auth-alt">Déjà inscrit ? <a href="{{ route('login', array_filter(['suite' => $suite ?? null])) }}">Connexion</a></p>
@endsection
