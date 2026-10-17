@extends('layouts.app')

@section('title', 'Politique de confidentialité')

@section('content')
@php($c = config('fabara.company'))
<section class="block">
    <div class="wrap legal">
        <span class="eyebrow">Version {{ config('fabara.privacy_version') }} · mise à jour le {{ config('fabara.privacy_updated_at') }}</span>
        <h1>Politique de confidentialité</h1>
        <p>Cette politique explique quelles informations {{ $c['name'] }} (« FABARA », « nous ») collecte sur ce site et dans ses futures applications mobiles, pourquoi, combien de temps elles sont gardées et comment vous pouvez les consulter, les corriger ou les supprimer. Elle s'applique conformément à la loi guinéenne L/2016/037/AN relative à la cybersécurité et à la protection des données à caractère personnel, et suit les exigences de Google Play et de l'App Store en matière de données des utilisateurs.</p>

        <h2>1. Qui est responsable de vos données</h2>
        <p>{{ $c['name'] }}, {{ $site['address'] }}. Contact pour toute question sur vos données : <b>{{ $site['email'] }}</b> ou <b class="mono">{{ $site['phone_display'] }}</b>.</p>

        <h2>2. Ce que nous collectons et pourquoi</h2>
        <div style="overflow-x:auto">
        <table>
            <thead><tr><th>Données</th><th>Quand</th><th>Pourquoi</th><th>Durée de conservation</th></tr></thead>
            <tbody>
                <tr><td>Nom, e-mail, téléphone, mot de passe (stocké sous forme chiffrée irréversible)</td><td>Création d'un compte</td><td>Vous identifier, vous donner accès à votre espace, vous recontacter</td><td>Jusqu'à la suppression du compte, ou {{ config('fabara.retention.inactive_accounts_years') }} ans sans connexion</td></tr>
                <tr><td>Détails du projet : lieu (commune), type de bien, travaux, surface, description</td><td>Demande de devis (depuis votre compte client)</td><td>Préparer la visite et établir le devis</td><td>Jusqu'à la suppression du compte</td></tr>
                <tr><td>Photos que vous envoyez</td><td>Seulement si vous choisissez d'en ajouter</td><td>Évaluer l'état du bâtiment</td><td>Comme la demande à laquelle elles sont jointes</td></tr>
                <tr><td>Messages échangés avec FABARA</td><td>Espace client</td><td>Suivre votre demande</td><td>Comme la demande concernée</td></tr>
                <tr><td>Date d'acceptation de cette politique, choix pour les offres commerciales</td><td>Inscription, modification du compte</td><td>Prouver votre consentement</td><td>Comme le compte</td></tr>
                <tr><td>Données techniques : adresse IP, navigateur, journal des erreurs</td><td>À chaque visite</td><td>Sécurité du site, lutte contre les abus</td><td>Session : {{ (int) (config('session.lifetime') / 60) ?: 2 }} h environ. Journaux : 14 jours</td></tr>
            </tbody>
        </table>
        </div>
        <p>Nous ne collectons <b>pas</b> votre position GPS, vos contacts, votre micro ou vos fichiers. Nous n'utilisons <b>aucun</b> outil de publicité ni de mesure d'audience qui vous suit d'un site à l'autre.</p>

        <h2>3. Base légale</h2>
        <ul>
            <li><b>Votre demande</b> : traiter votre demande de devis et gérer votre compte (mesures précontractuelles et contrat).</li>
            <li><b>Votre consentement</b> : offres commerciales (facultatif, retirable à tout moment dans « Mon compte »).</li>
            <li><b>Notre intérêt légitime</b> : sécuriser le site et prévenir la fraude.</li>
        </ul>

        <h2>4. Qui peut voir vos données</h2>
        <ul>
            <li>Uniquement l'équipe FABARA habilitée, via un espace d'administration protégé.</li>
            <li>Nos prestataires techniques, uniquement pour faire fonctionner le service : l'hébergeur du site et le service d'envoi d'e-mails. Ils ne peuvent pas utiliser vos données pour leur propre compte.</li>
            <li>Si vous choisissez de nous écrire sur WhatsApp, ces échanges passent par WhatsApp (Meta) et sont soumis à sa propre politique de confidentialité.</li>
        </ul>
        <p>Nous ne <b>vendons</b>, ne louons et ne partageons jamais vos données à des fins publicitaires. Nous ne les communiquons à une autorité que si la loi l'exige.</p>
        <p>L'hébergeur du site peut se trouver hors de Guinée. Dans ce cas, nous choisissons un prestataire qui protège les données à un niveau équivalent.</p>

        <h2>5. Sécurité</h2>
        <ul>
            <li>Connexion chiffrée (HTTPS) sur tout le site.</li>
            <li>Mots de passe stockés sous forme hachée : personne, même chez FABARA, ne peut les lire.</li>
            <li>Photos des demandes stockées dans un espace privé, visibles seulement par vous et l'équipe FABARA.</li>
            <li>Limitation des tentatives de connexion et protection contre les formulaires automatisés.</li>
        </ul>

        <h2>6. Vos droits</h2>
        <p>Vous pouvez à tout moment :</p>
        <ul>
            <li><b>Consulter et télécharger</b> vos données : « Mon espace » › « Mon compte et mes données » › « Télécharger mes données ».</li>
            <li><b>Corriger</b> vos informations : même page, « Mes informations ».</li>
            <li><b>Supprimer</b> votre compte et toutes vos données : même page, « Supprimer mon compte ». Sans accès à votre compte, utilisez la page <a href="{{ route('deletion') }}">Suppression de compte</a>.</li>
            <li><b>Retirer votre consentement</b> aux offres commerciales et <b>vous opposer</b> à un traitement en nous écrivant.</li>
        </ul>
        <p>Nous répondons à toute demande sous 30 jours maximum.</p>

        <h2>7. Cookies</h2>
        <p>Le site utilise uniquement des cookies indispensables à son fonctionnement : le cookie de session (rester connecté pendant la visite), le jeton de sécurité des formulaires et, si vous cochez « Rester connecté », un cookie de connexion. Aucun cookie publicitaire ni de mesure d'audience. Ces cookies ne nécessitent pas de consentement préalable.</p>

        <h2>8. Mineurs</h2>
        <p>Nos services s'adressent aux personnes majeures (18 ans et plus). Nous ne collectons pas sciemment de données concernant des mineurs. Si vous pensez qu'un mineur nous a transmis des informations, contactez-nous pour les faire supprimer.</p>

        <h2>9. Applications mobiles</h2>
        <p>Si FABARA publie une application Android ou iOS, elle utilisera le même compte et la même politique. Elle ne demandera l'accès à l'appareil photo ou aux photos que lorsque vous choisissez d'ajouter une photo à une demande, et jamais en arrière-plan.</p>

        <h2>10. Modifications</h2>
        <p>Si cette politique change de façon importante, nous vous en informerons sur le site ou par e-mail avant l'entrée en vigueur. La date de version figure en haut de cette page.</p>
    </div>
</section>
@endsection
