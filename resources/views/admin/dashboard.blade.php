@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="page-title">
    <div>
        <h1>Tableau de bord</h1>
        <p>{{ now()->translatedFormat('l d F Y') }}</p>
    </div>
    <a class="btn btn-gold" href="{{ route('admin.devis.index', ['statut' => 'nouveau']) }}">Voir les nouvelles demandes</a>
</div>

<div class="stats">
    <div class="stat {{ ($counts['nouveau'] ?? 0) > 0 ? 'alert' : '' }}"><b>{{ $counts['nouveau'] ?? 0 }}</b><span>Nouvelles demandes</span></div>
    <div class="stat {{ $unreadMessages > 0 ? 'alert' : '' }}"><b>{{ $unreadMessages }}</b><span>Messages clients non lus</span></div>
    <div class="stat"><b>{{ $thisMonth }}</b><span>Demandes ce mois-ci</span></div>
    <div class="stat"><b>{{ $total }}</b><span>Demandes au total</span></div>
    <div class="stat"><b>{{ $clients }}</b><span>Comptes clients</span></div>
    <div class="stat"><b style="font-size:22px">{{ number_format($wonAmount, 0, ',', ' ') }}</b><span>GNF de devis acceptés</span></div>
    @if ($pendingDeletions > 0)
        <a class="stat alert" href="{{ route('admin.deletions.index') }}" style="text-decoration:none;color:inherit"><b>{{ $pendingDeletions }}</b><span>Demande(s) de suppression à traiter</span></a>
    @endif
</div>

<div class="pipe" style="grid-template-columns:repeat(auto-fit,minmax(130px,1fr))">
    @foreach (config('fabara.statuses') as $key => [$label, $color])
        <a class="c-{{ $color }}" href="{{ route('admin.devis.index', ['statut' => $key]) }}" style="background:var(--surface);border:1px solid var(--line);border-top:4px solid var(--c);border-radius:10px;padding:14px;display:grid;gap:6px;color:var(--ink)">
            <b>{{ $counts[$key] ?? 0 }}</b><span>{{ $label }}</span>
        </a>
    @endforeach
</div>

<div class="panel">
    <h2>Dernières demandes</h2>
    @if ($latest->isEmpty())
        <div class="empty"><b>Aucune demande pour l'instant</b><span>Les demandes envoyées depuis le site apparaîtront ici.</span></div>
    @else
        <div class="table-wrap" style="border:0">
            <table class="t">
                <thead><tr><th>Réf.</th><th>Client</th><th>Travaux</th><th>Lieu</th><th>Statut</th><th>Date</th></tr></thead>
                <tbody>
                @foreach ($latest as $d)
                    <tr>
                        <td class="num"><a href="{{ route('admin.devis.show', $d) }}">{{ $d->reference }}</a></td>
                        <td>{{ $d->name }}<br><span class="muted mono" style="font-size:13px">{{ $d->phone }}</span></td>
                        <td>{{ $d->worksList() }}</td>
                        <td>{{ $d->commune }}</td>
                        <td><span class="pill c-{{ $d->statusColor() }}">{{ $d->statusLabel() }}</span></td>
                        <td class="num">{{ $d->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
