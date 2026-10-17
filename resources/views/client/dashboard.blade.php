@extends('layouts.client')

@section('title', 'Mon espace')

@section('page')
<div class="page-title">
    <div>
        <h1>Bonjour {{ $user->name }}</h1>
        <p>Suivez ici vos demandes de devis et les réponses de FABARA.</p>
    </div>
    <a class="btn btn-gold" href="{{ route('client.devis.create') }}">+ Nouvelle demande</a>
</div>

@if ($unread > 0)
    <div class="flash flash-ok">Vous avez {{ $unread }} nouveau{{ $unread > 1 ? 'x' : '' }} message{{ $unread > 1 ? 's' : '' }} de FABARA.</div>
@endif

@if ($devisList->isEmpty())
    <div class="empty">
        <b>Aucune demande pour l'instant</b>
        <span>Décrivez votre projet, ajoutez quelques photos : nous vous recontactons pour la visite gratuite.</span>
        <a class="btn btn-gold" href="{{ route('client.devis.create') }}">Faire ma première demande</a>
    </div>
@else
    <div class="list">
        @foreach ($devisList as $d)
            <a class="lead c-{{ $d->statusColor() }}" href="{{ route('client.devis.show', $d->reference) }}" style="text-decoration:none;color:inherit">
                <div class="lead-h">
                    <div style="display:grid;gap:4px">
                        <b>{{ $d->worksList() }}</b>
                        <div class="meta">
                            <span class="mono">{{ $d->reference }}</span>
                            <span>{{ $d->commune }} · {{ $d->property_type }}</span>
                            <span>{{ $d->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    <div style="display:grid;gap:6px;justify-items:end">
                        <span class="pill">{{ $d->statusLabel() }}</span>
                        @if ($d->unread_count)<span class="badge">{{ $d->unread_count }} nouveau(x)</span>@endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
