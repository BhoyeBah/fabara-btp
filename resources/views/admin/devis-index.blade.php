@extends('layouts.admin')

@section('title', 'Demandes')

@section('content')
<div class="page-title">
    <div>
        <h1>Demandes de devis</h1>
        <p>{{ $devisList->total() }} demande(s){{ $status ? ' · '.config('fabara.statuses.'.$status.'.0') : '' }}</p>
    </div>
</div>

<form class="toolbar" method="GET" action="{{ route('admin.devis.index') }}">
    <input type="search" name="q" value="{{ $q }}" placeholder="Rechercher : nom, téléphone, référence, commune…" aria-label="Rechercher">
    <select name="statut" aria-label="Filtrer par statut">
        <option value="">Tous les statuts</option>
        @foreach (config('fabara.statuses') as $key => [$label])
            <option value="{{ $key }}" @selected($status === $key)>{{ $label }} ({{ $counts[$key] ?? 0 }})</option>
        @endforeach
    </select>
    <button class="btn btn-quiet" type="submit">Filtrer</button>
    @if ($q || $status)<a class="btn btn-quiet" href="{{ route('admin.devis.index') }}">Effacer</a>@endif
</form>

@if ($devisList->isEmpty())
    <div class="empty"><b>Aucune demande ne correspond</b><span>Modifiez la recherche ou le filtre.</span></div>
@else
    <div class="table-wrap">
        <table class="t">
            <thead><tr><th>Réf.</th><th>Client</th><th>Travaux</th><th>Lieu</th><th>Compte</th><th>Statut</th><th>Date</th></tr></thead>
            <tbody>
            @foreach ($devisList as $d)
                <tr>
                    <td class="num"><a href="{{ route('admin.devis.show', $d) }}">{{ $d->reference }}</a>
                        @if ($d->unread_count)<br><span class="badge">{{ $d->unread_count }} msg</span>@endif
                    </td>
                    <td>{{ $d->name }}<br><span class="muted mono" style="font-size:13px">{{ $d->phone }}</span></td>
                    <td>{{ $d->worksList() }}</td>
                    <td>{{ $d->commune }}<br><span class="muted" style="font-size:13px">{{ $d->property_type }}</span></td>
                    <td>{{ $d->user_id ? 'Oui' : 'Sans compte' }}</td>
                    <td><span class="pill c-{{ $d->statusColor() }}">{{ $d->statusLabel() }}</span></td>
                    <td class="num">{{ $d->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $devisList->links() }}
@endif
@endsection
