@extends('layouts.app')

@section('title', "Conditions d'utilisation")

@section('content')
@php($c = config('fabara.company'))
<section class="block">
    <div class="wrap legal">
        <span class="eyebrow">Mise à jour le {{ config('fabara.privacy_updated_at') }}</span>
        <h1>Conditions d'utilisation</h1>

        <h2>1. Objet</h2>
        <p>Ce site permet de découvrir les services de {{ $c['name'] }}, de demander un devis gratuit et, avec un compte client, de suivre ses demandes et d'échanger avec FABARA.</p>

        <h2>2. Compte client</h2>
        <ul>
            <li>Le compte est gratuit et réservé aux personnes majeures.</li>
            <li>Vous vous engagez à fournir des informations exactes et à garder votre mot de passe confidentiel.</li>
            <li>Vous pouvez supprimer votre compte à tout moment depuis « Mon compte et mes données ».</li>
            <li>FABARA peut suspendre un compte utilisé de manière abusive (faux renseignements, propos injurieux, envois massifs).</li>
        </ul>

        <h2>3. Demandes de devis</h2>
        <p>Une demande envoyée sur le site ne vaut pas commande. Le prix et le délai des travaux sont fixés dans un devis écrit établi après la visite du chantier. Les estimations du calculateur sont indicatives.</p>

        <h2>4. Photos et contenus envoyés</h2>
        <p>Les photos et messages que vous envoyez servent uniquement à traiter votre demande. FABARA ne les publie jamais sans votre accord écrit. N'envoyez pas de photos montrant d'autres personnes sans leur accord.</p>

        <h2>5. Propriété du site</h2>
        <p>Les textes, logos et photos du site appartiennent à FABARA. Toute reproduction sans autorisation est interdite.</p>

        <h2>6. Données personnelles</h2>
        <p>Leur traitement est décrit dans la <a href="{{ route('privacy') }}">politique de confidentialité</a>.</p>

        <h2>7. Droit applicable</h2>
        <p>Ces conditions sont soumises au droit guinéen. En cas de désaccord, nous cherchons d'abord une solution amiable ; à défaut, les tribunaux de Conakry sont compétents.</p>

        <h2>8. Contact</h2>
        <p>{{ $site['email'] }} · <span class="mono">{{ $site['phone_display'] }}</span> · {{ $site['address'] }}</p>
    </div>
</section>
@endsection
