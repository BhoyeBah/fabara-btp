@extends('layouts.admin')

@section('title', 'Demande '.$devis->reference)

@section('content')
<div class="page-title">
    <div>
        <p class="crumb"><a href="{{ route('admin.devis.index') }}">Demandes</a> › {{ $devis->reference }}</p>
        <h1>{{ $devis->name }}</h1>
        <p>{{ $devis->worksList() }} · reçue le {{ $devis->created_at->translatedFormat('d F Y à H:i') }}</p>
    </div>
    <div class="lead-actions">
        <span class="pill c-{{ $devis->statusColor() }}" style="font-size:14px;padding:8px 12px">{{ $devis->statusLabel() }}</span>
        <a class="btn btn-wa btn-sm" href="{{ $devis->clientWhatsappUrl('Bonjour '.$devis->name.', FABARA vous contacte au sujet de votre demande '.$devis->reference.'.') }}" target="_blank" rel="noopener"><svg><use href="#i-wa"/></svg>WhatsApp client</a>
    </div>
</div>

<div class="cols">
    <div style="display:grid;gap:22px">
        <div class="panel">
            <h2>Demande</h2>
            <dl class="dl">
                <dt>Téléphone</dt><dd class="mono" style="user-select:all">{{ $devis->phone }}</dd>
                @if ($devis->email)<dt>E-mail</dt><dd>{{ $devis->email }}</dd>@endif
                <dt>Lieu</dt><dd>{{ $devis->commune }}</dd>
                <dt>Type de bien</dt><dd>{{ $devis->property_type }}</dd>
                <dt>Travaux</dt><dd>{{ $devis->worksList() }}</dd>
                @if ($devis->surface)<dt>Surface</dt><dd class="mono">{{ rtrim(rtrim((string) $devis->surface, '0'), '.') }} m²</dd>@endif
                @if ($devis->start_when)<dt>Démarrage</dt><dd>{{ $devis->start_when }}</dd>@endif
                <dt>Compte client</dt><dd>@if ($devis->user)<a href="{{ route('admin.clients.show', $devis->user) }}">{{ $devis->user->name }}</a>@else Demande sans compte @endif</dd>
                <dt>Consentement</dt><dd>{{ $devis->consent_at->translatedFormat('d/m/Y H:i') }}</dd>
            </dl>
            @if ($devis->message)<p style="white-space:pre-wrap">{{ $devis->message }}</p>@endif
            @if ($devis->photos->isNotEmpty())
                <div class="photos">
                    @foreach ($devis->photos as $p)
                        <a href="{{ route('photos.show', $p) }}" target="_blank"><img src="{{ route('photos.show', $p) }}" alt="Photo {{ $loop->iteration }} de la demande" loading="lazy"></a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="panel" id="messages">
            <h2>Messages</h2>
            @if (! $devis->user)
                <p class="muted">Ce client n'a pas de compte : il ne verra pas les messages écrits ici. Contactez-le par téléphone ou WhatsApp.</p>
            @endif
            <div class="thread">
                @forelse ($devis->messages as $m)
                    <div class="bubble {{ $m->from_admin ? 'me' : 'them' }}">
                        <small>{{ $m->from_admin ? 'FABARA' : $devis->name }} · {{ $m->created_at->translatedFormat('d M Y, H:i') }}</small>
                        <p>{{ $m->body }}</p>
                    </div>
                @empty
                    <p class="muted">Aucun message pour cette demande.</p>
                @endforelse
            </div>
            @if ($devis->user)
                <form class="thread-form" method="POST" action="{{ route('admin.devis.message', $devis) }}">
                    @csrf
                    <label class="f" for="body">Répondre au client
                        <textarea id="body" name="body" required maxlength="2000" placeholder="Ex. Bonjour, nous pouvons passer samedi à 10 h pour la visite.">{{ old('body') }}</textarea>
                    </label>
                    <button class="btn btn-gold" type="submit" style="justify-self:start">Envoyer la réponse</button>
                </form>
            @endif
        </div>
    </div>

    <div style="display:grid;gap:22px">
        <form class="panel" method="POST" action="{{ route('admin.devis.update', $devis) }}">
            @csrf @method('PUT')
            <h2>Suivi</h2>
            <label class="f" for="status">Statut
                <select id="status" name="status">
                    @foreach (config('fabara.statuses') as $key => [$label])
                        <option value="{{ $key }}" @selected($devis->status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="f" for="quoted_amount">Montant du devis <small>(GNF, visible par le client)</small>
                <input id="quoted_amount" name="quoted_amount" inputmode="numeric" value="{{ old('quoted_amount', $devis->quoted_amount) }}" placeholder="Ex. 4500000">
            </label>
            <label class="f" for="admin_notes">Notes internes <small>(jamais visibles par le client)</small>
                <textarea id="admin_notes" name="admin_notes" maxlength="5000" placeholder="Date de visite, métré, matériaux choisis…">{{ old('admin_notes', $devis->admin_notes) }}</textarea>
            </label>
            <button class="btn btn-gold" type="submit" style="justify-self:start">Enregistrer</button>
        </form>

        <form class="danger-zone" method="POST" action="{{ route('admin.devis.destroy', $devis) }}" data-confirm="Supprimer définitivement la demande {{ $devis->reference }}, ses photos et ses messages ?">
            @csrf @method('DELETE')
            <h2>Supprimer la demande</h2>
            <p>Efface la demande, ses photos et ses messages.</p>
            <button class="btn btn-danger btn-sm" type="submit" style="justify-self:start">Supprimer</button>
        </form>
    </div>
</div>
@endsection
