@extends('layouts.auth')

@section('title', 'Nouveau mot de passe')

@section('form')
    <div>
        <h1>Nouveau mot de passe</h1>
        <p class="sub">Choisissez un mot de passe d'au moins 8 caractères, avec une lettre et un chiffre.</p>
    </div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label class="f" for="email">E-mail
            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', $email) }}">
        </label>
        <label class="f" for="password">Nouveau mot de passe
            <input id="password" name="password" type="password" autocomplete="new-password" required minlength="8">
        </label>
        <label class="f" for="password_confirmation">Confirmation
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8">
        </label>
        <button class="btn btn-gold btn-block" type="submit">Enregistrer</button>
    </form>
@endsection
