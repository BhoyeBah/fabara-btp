{{-- Bloc « demande de devis » : formulaire pour un client connecté, invitation à créer un compte sinon.
     Variables : $preset (facultatif), $suite ('devis' ou 'renovation'), $submitLabel --}}
@php
    $preset = $preset ?? [];
    $suite = $suite ?? 'devis';
    $user = auth()->user();
@endphp

@if ($user && ! $user->isAdmin())
    <form class="card" id="devisForm" method="POST" action="{{ route('devis.public.store') }}" enctype="multipart/form-data">
        @csrf
        @include('partials.devis-fields', ['user' => $user, 'askConsent' => false, 'preset' => $preset])
        <button class="btn btn-gold" type="submit" style="justify-self:start">{{ $submitLabel ?? 'Envoyer ma demande' }}</button>
    </form>
@elseif ($user)
    <div class="card" style="display:grid;gap:12px;background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:28px">
        <h3 style="font-size:24px;text-transform:uppercase">Formulaire réservé aux clients</h3>
        <p class="muted">Vous êtes connecté en tant qu'administrateur. Les clients voient ici le formulaire de demande de devis.</p>
        <a class="btn btn-quiet" href="{{ route('admin.devis.index') }}" style="justify-self:start">Voir les demandes</a>
    </div>
@else
    <div class="card gate-card">
        <span class="stamp" style="justify-self:start">Devis gratuit</span>
        <h3>Créez votre compte pour demander un devis</h3>
        <p>C'est gratuit et rapide (1 minute). Avec votre compte, vous :</p>
        <ul class="gate-list">
            <li>décrivez votre projet et envoyez vos photos ;</li>
            <li>suivez l'avancement de votre demande : visite, devis, chantier ;</li>
            <li>échangez directement avec l'équipe FABARA ;</li>
            <li>retrouvez tous vos devis au même endroit.</li>
        </ul>
        <div class="hero-cta">
            <a class="btn btn-gold" href="{{ route('register', ['suite' => $suite]) }}">Créer mon compte et demander un devis</a>
            <a class="btn btn-quiet" href="{{ route('login', ['suite' => $suite]) }}">J'ai déjà un compte</a>
        </div>
        <p class="muted" style="font-size:14px">Une simple question ? Écrivez-nous sur <a data-wa="Bonjour FABARA, j'ai une question." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener" style="color:var(--gold-ink);font-weight:600">WhatsApp</a>.</p>
    </div>
@endif
