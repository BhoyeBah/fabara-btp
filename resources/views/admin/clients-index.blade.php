@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
<div class="page-title">
    <div>
        <h1>Clients</h1>
        <p>{{ $clients->total() }} compte(s) client</p>
    </div>
</div>

<form class="toolbar" method="GET" action="{{ route('admin.clients.index') }}">
    <input type="search" name="q" value="{{ $q }}" placeholder="Rechercher : nom, e-mail, téléphone…" aria-label="Rechercher un client">
    <button class="btn btn-quiet" type="submit">Rechercher</button>
</form>

@if ($clients->isEmpty())
    <div class="empty"><b>Aucun client</b><span>Les clients qui s'inscrivent sur le site apparaîtront ici.</span></div>
@else
    <div class="table-wrap">
        <table class="t">
            <thead><tr><th>Nom</th><th>Contact</th><th>Demandes</th><th>Offres</th><th>Inscrit le</th><th>Dernière connexion</th></tr></thead>
            <tbody>
            @foreach ($clients as $c)
                <tr>
                    <td><a href="{{ route('admin.clients.show', $c) }}">{{ $c->name }}</a></td>
                    <td>{{ $c->email }}<br><span class="muted mono" style="font-size:13px">{{ $c->phone }}</span></td>
                    <td class="num">{{ $c->devis_requests_count }}</td>
                    <td>{{ $c->marketing_opt_in ? 'Accepte' : 'Non' }}</td>
                    <td class="num">{{ $c->created_at->format('d/m/Y') }}</td>
                    <td class="num">{{ $c->last_login_at?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $clients->links() }}
@endif
@endsection
