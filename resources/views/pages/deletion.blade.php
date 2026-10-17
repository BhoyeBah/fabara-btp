@extends('layouts.app')

@section('title', 'Suppression de compte')

@section('content')
<section class="block">
    <div class="wrap legal">
        <span class="eyebrow">{{ config('fabara.company.name') }}</span>
        <h1>Supprimer votre compte et vos données</h1>
        <p>Cette page explique comment supprimer votre compte FABARA (site web et application mobile) et ce qu'il advient de vos données.</p>

        <h2>Si vous pouvez vous connecter</h2>
        <ul>
            <li>Connectez-vous sur la page <a href="{{ route('login') }}">Connexion</a> (ou dans l'application).</li>
            <li>Ouvrez « Mon espace » › « Mon compte et mes données ».</li>
            <li>Dans « Supprimer mon compte », saisissez votre mot de passe et confirmez.</li>
        </ul>
        <p>La suppression est immédiate et définitive.</p>

        <h2>Si vous ne pouvez plus vous connecter</h2>
        <p>Remplissez le formulaire ci-dessous. Nous vérifions que la demande vient bien du titulaire du compte, puis nous supprimons le compte sous 30 jours maximum et vous le confirmons.</p>

        <form class="card" method="POST" action="{{ route('deletion.store') }}" style="max-width:620px">
            @csrf
            <label class="f" for="del-name">Nom complet<input id="del-name" name="name" required maxlength="120" value="{{ old('name') }}"></label>
            <label class="f" for="del-contact">E-mail ou téléphone du compte<input id="del-contact" name="contact" required maxlength="190" value="{{ old('contact') }}"></label>
            <label class="f" for="del-msg">Précisions <small>(facultatif)</small><textarea id="del-msg" name="message" maxlength="1000">{{ old('message') }}</textarea></label>
            <label class="check" for="del-confirm"><input id="del-confirm" type="checkbox" name="confirm" value="1" required><span>Je demande la suppression définitive de mon compte et de mes données.</span></label>
            <button class="btn btn-danger" type="submit" style="justify-self:start">Envoyer la demande de suppression</button>
        </form>
        <p>Vous pouvez aussi écrire à <b>{{ $site['email'] }}</b> ou sur WhatsApp au <b class="mono">{{ $site['phone_display'] }}</b>.</p>

        <h2>Données supprimées</h2>
        <ul>
            <li>Votre compte : nom, e-mail, téléphone, mot de passe, préférences.</li>
            <li>Toutes vos demandes de devis, les photos jointes et les messages échangés.</li>
            <li>Vos sessions de connexion en cours.</li>
        </ul>

        <h2>Données conservées</h2>
        <p>Aucune donnée de votre compte n'est conservée après la suppression. Seule la demande de suppression elle-même (nom et contact indiqués dans ce formulaire) est gardée {{ config('fabara.retention.deletion_requests_days') }} jours après son traitement, pour prouver que nous y avons répondu, puis effacée automatiquement. Si une facture a été émise pour des travaux réalisés, elle est conservée séparément pendant la durée imposée par la loi comptable.</p>
    </div>
</section>
@endsection
