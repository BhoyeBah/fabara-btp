@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('form')
    <div>
        <h1>Mot de passe oublié</h1>
        <p class="sub">Indiquez l'e-mail de votre compte. Nous vous envoyons un lien pour choisir un nouveau mot de passe.</p>
    </div>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label class="f" for="email">E-mail
            <input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}">
        </label>
        <button class="btn btn-gold btn-block" type="submit">Recevoir le lien</button>
    </form>
    <p class="auth-alt"><a href="{{ route('login') }}">Retour à la connexion</a></p>
@endsection
