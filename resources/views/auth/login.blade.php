@extends('layouts.auth')

@section('title', 'Connexion')

@section('form')
    <div>
        <h1>Connexion</h1>
        <p class="sub">Accédez à votre espace client.</p>
        @if (!empty($suite))<div class="flash flash-ok" style="margin-top:12px">Pour demander un devis, connectez-vous ou créez votre compte. Vous arriverez ensuite directement sur le formulaire.</div>@endif
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label class="f" for="email">E-mail
            <input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}">
        </label>
        <label class="f" for="password">Mot de passe
            <input id="password" name="password" type="password" autocomplete="current-password" required>
        </label>
        <label class="check" for="remember"><input id="remember" type="checkbox" name="remember" value="1"><span>Rester connecté sur cet appareil</span></label>
        <button class="btn btn-gold btn-block" type="submit">Se connecter</button>
    </form>
    <p class="auth-alt"><a href="{{ route('password.request') }}">Mot de passe oublié ?</a></p>
    <p class="auth-alt">Pas encore de compte ? <a href="{{ route('register', array_filter(['suite' => $suite ?? null])) }}">Inscription</a></p>
@endsection
