@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')
<div class="page-title">
    <div>
        <h1>Paramètres du site</h1>
        <p>Ces textes et coordonnées s'affichent sur tout le site.</p>
    </div>
</div>

<div class="cols">
    <form class="panel" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')
        <h2>Textes et coordonnées</h2>
        <label class="f" for="slogan">Slogan<input id="slogan" name="slogan" required maxlength="120" value="{{ old('slogan', $values['slogan']) }}"></label>
        <label class="f" for="promise">Promesse (badge doré)<input id="promise" name="promise" required maxlength="60" value="{{ old('promise', $values['promise']) }}"></label>
        <label class="f" for="hero_text">Texte d'accueil<textarea id="hero_text" name="hero_text" required maxlength="400">{{ old('hero_text', $values['hero_text']) }}</textarea></label>
        <label class="f" for="announcement">Bandeau d'annonce <small>(facultatif, ex. promotion du mois — laissez vide pour le masquer)</small><input id="announcement" name="announcement" maxlength="160" value="{{ old('announcement', $values['announcement']) }}"></label>
        <div class="grid2">
            <label class="f" for="phone_display">Téléphone affiché<input id="phone_display" name="phone_display" required maxlength="30" value="{{ old('phone_display', $values['phone_display']) }}"></label>
            <label class="f" for="whatsapp">Numéro WhatsApp <small>(chiffres seulement, avec 224)</small><input id="whatsapp" name="whatsapp" required inputmode="numeric" value="{{ old('whatsapp', $values['whatsapp']) }}"></label>
        </div>
        <div class="grid2">
            <label class="f" for="email">E-mail de contact<input id="email" name="email" type="email" maxlength="190" value="{{ old('email', $values['email']) }}"></label>
            <label class="f" for="hours">Horaires<input id="hours" name="hours" maxlength="120" value="{{ old('hours', $values['hours']) }}"></label>
        </div>
        <label class="f" for="address">Adresse<input id="address" name="address" required maxlength="190" value="{{ old('address', $values['address']) }}"></label>
        <button class="btn btn-gold" type="submit" style="justify-self:start">Enregistrer</button>
    </form>

    <div style="display:grid;gap:22px">
        <form class="panel" method="POST" action="{{ route('admin.settings.password') }}">
            @csrf @method('PUT')
            <h2>Mon mot de passe</h2>
            <label class="f" for="current_password">Mot de passe actuel<input id="current_password" name="current_password" type="password" autocomplete="current-password" required></label>
            <label class="f" for="password">Nouveau mot de passe<input id="password" name="password" type="password" autocomplete="new-password" required minlength="8"></label>
            <label class="f" for="password_confirmation">Confirmation<input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8"></label>
            <button class="btn btn-quiet" type="submit" style="justify-self:start">Changer</button>
        </form>
        <div class="panel">
            <h2>Accès administration</h2>
            <p class="muted">Connectez-vous depuis la page « Connexion » du site avec votre compte admin : vous arrivez directement ici. Pour tout autre visiteur, cette adresse affiche « Page introuvable ».</p>
            <dl class="dl"><dt>Adresse</dt><dd class="mono">/{{ config('fabara.admin_path') }}</dd></dl>
        </div>
    </div>
</div>
@endsection
