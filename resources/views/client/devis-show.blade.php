@extends('layouts.client')

@section('title', 'Demande '.$devis->reference)

@section('page')
<div class="page-title">
    <div>
        <p class="crumb"><a href="{{ route('client.dashboard') }}">Mes demandes</a> › {{ $devis->reference }}</p>
        <h1>{{ $devis->worksList() }}</h1>
        <p>Envoyée le {{ $devis->created_at->translatedFormat('d F Y à H:i') }}</p>
    </div>
    <span class="pill c-{{ $devis->statusColor() }}" style="font-size:14px;padding:8px 12px">{{ $devis->statusLabel() }}</span>
</div>

<div class="cols">
    <div class="panel" id="messages">
        <h2>Échanges avec FABARA</h2>
        <div class="thread">
            @forelse ($devis->messages as $m)
                <div class="bubble {{ $m->from_admin ? 'them' : 'me' }}">
                    <small>{{ $m->from_admin ? 'FABARA' : 'Vous' }} · {{ $m->created_at->translatedFormat('d M Y, H:i') }}</small>
                    <p>{{ $m->body }}</p>
                </div>
            @empty
                <p class="muted">Pas encore de message. Posez votre question ci-dessous, ou attendez notre appel pour fixer la visite.</p>
            @endforelse
        </div>
        <form class="thread-form" method="POST" action="{{ route('client.devis.message', $devis->reference) }}">
            @csrf
            <label class="f" for="body">Votre message
                <textarea id="body" name="body" required maxlength="2000" placeholder="Ex. Je suis disponible samedi matin pour la visite.">{{ old('body') }}</textarea>
            </label>
            <button class="btn btn-gold" type="submit" style="justify-self:start">Envoyer</button>
        </form>
    </div>

    <div style="display:grid;gap:22px">
        <div class="panel">
            <h2>Votre demande</h2>
            <dl class="dl">
                <dt>Référence</dt><dd class="mono">{{ $devis->reference }}</dd>
                <dt>Lieu</dt><dd>{{ $devis->commune }}</dd>
                <dt>Type de bien</dt><dd>{{ $devis->property_type }}</dd>
                <dt>Travaux</dt><dd>{{ $devis->worksList() }}</dd>
                @if ($devis->surface)<dt>Surface</dt><dd class="mono">{{ rtrim(rtrim((string) $devis->surface, '0'), '.') }} m²</dd>@endif
                @if ($devis->start_when)<dt>Démarrage</dt><dd>{{ $devis->start_when }}</dd>@endif
                @if ($devis->quoted_amount)<dt>Montant du devis</dt><dd class="mono">{{ number_format($devis->quoted_amount, 0, ',', ' ') }} GNF</dd>@endif
            </dl>
            @if ($devis->message)
                <p style="white-space:pre-wrap">{{ $devis->message }}</p>
            @endif
        </div>
        @if ($devis->photos->isNotEmpty())
            <div class="panel">
                <h2>Vos photos</h2>
                <div class="photos">
                    @foreach ($devis->photos as $p)
                        <a href="{{ route('photos.show', $p) }}" target="_blank"><img src="{{ route('photos.show', $p) }}" alt="Photo envoyée {{ $loop->iteration }}" loading="lazy"></a>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="panel">
            <h2>Besoin d'aller plus vite ?</h2>
            <a class="btn btn-wa" data-wa="Bonjour FABARA, je vous contacte au sujet de ma demande {{ $devis->reference }}." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener" style="justify-self:start"><svg><use href="#i-wa"/></svg>Écrire sur WhatsApp</a>
        </div>
    </div>
</div>
@endsection
