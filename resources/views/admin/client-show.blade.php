@extends('layouts.admin')

@section('title', $client->name)

@section('content')
<div class="page-title">
    <div>
        <p class="crumb"><a href="{{ route('admin.clients.index') }}">Clients</a> › {{ $client->name }}</p>
        <h1>{{ $client->name }}</h1>
    </div>
</div>

<div class="cols">
    <div class="panel">
        <h2>Demandes</h2>
        @forelse ($devisList as $d)
            <a class="lead c-{{ $d->statusColor() }}" href="{{ route('admin.devis.show', $d) }}" style="text-decoration:none;color:inherit">
                <div class="lead-h">
                    <div><b>{{ $d->worksList() }}</b><div class="meta"><span class="mono">{{ $d->reference }}</span><span>{{ $d->commune }}</span><span>{{ $d->created_at->format('d/m/Y') }}</span></div></div>
                    <span class="pill">{{ $d->statusLabel() }}</span>
                </div>
            </a>
        @empty
            <p class="muted">Ce client n'a pas encore fait de demande.</p>
        @endforelse
    </div>

    <div style="display:grid;gap:22px">
        <div class="panel">
            <h2>Compte</h2>
            <dl class="dl">
                <dt>E-mail</dt><dd>{{ $client->email }}</dd>
                <dt>Téléphone</dt><dd class="mono">{{ $client->phone }}</dd>
                <dt>Inscrit le</dt><dd>{{ $client->created_at->translatedFormat('d F Y') }}</dd>
                <dt>Conditions acceptées</dt><dd>{{ $client->terms_accepted_at?->translatedFormat('d/m/Y H:i') ?? '—' }} (v. {{ $client->privacy_version ?? '—' }})</dd>
                <dt>Offres commerciales</dt><dd>{{ $client->marketing_opt_in ? 'Accepte' : 'Refuse' }}</dd>
                <dt>Dernière connexion</dt><dd>{{ $client->last_login_at?->translatedFormat('d/m/Y H:i') ?? '—' }}</dd>
            </dl>
        </div>
        <form class="danger-zone" method="POST" action="{{ route('admin.clients.destroy', $client) }}">
            @csrf @method('DELETE')
            <h2>Supprimer ce compte</h2>
            <p>À utiliser quand le client vous demande la suppression de ses données (par téléphone, WhatsApp ou via le formulaire public). Le compte, les demandes, les photos et les messages sont effacés définitivement.</p>
            <label class="f" for="confirm_email">Recopiez l'e-mail du client pour confirmer<input id="confirm_email" name="confirm_email" required placeholder="{{ $client->email }}"></label>
            <button class="btn btn-danger btn-sm" type="submit" style="justify-self:start">Supprimer définitivement</button>
        </form>
    </div>
</div>
@endsection
