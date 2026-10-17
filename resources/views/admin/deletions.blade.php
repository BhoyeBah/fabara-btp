@extends('layouts.admin')

@section('title', 'Demandes de suppression')

@section('content')
<div class="page-title">
    <div>
        <h1>Demandes de suppression</h1>
        <p>Envoyées depuis la page publique « Supprimer mon compte » par des personnes qui ne peuvent pas se connecter. Délai de traitement annoncé : 30 jours maximum.</p>
    </div>
</div>

@if ($requests->isEmpty())
    <div class="empty"><b>Aucune demande</b><span>Les demandes de suppression apparaîtront ici.</span></div>
@else
    <div class="table-wrap">
        <table class="t">
            <thead><tr><th>Reçue le</th><th>Nom</th><th>Contact</th><th>Message</th><th>Compte trouvé</th><th>Statut</th></tr></thead>
            <tbody>
            @foreach ($requests as $r)
                <tr>
                    <td class="num">{{ $r->created_at->format('d/m/Y') }}</td>
                    <td>{{ $r->name }}</td>
                    <td class="mono" style="font-size:14px">{{ $r->contact }}</td>
                    <td style="max-width:280px">{{ $r->message }}</td>
                    <td>
                        @if ($matches[$r->id] ?? null)
                            <a href="{{ route('admin.clients.show', $matches[$r->id]) }}">{{ $matches[$r->id]->name }}</a>
                            <br><span class="muted" style="font-size:13px">Vérifiez l'identité, puis supprimez depuis la fiche client.</span>
                        @else
                            <span class="muted">Aucun compte correspondant</span>
                        @endif
                    </td>
                    <td>
                        @if ($r->status === 'en_attente')
                            <form method="POST" action="{{ route('admin.deletions.update', $r) }}">
                                @csrf @method('PUT')
                                <button class="btn btn-gold btn-sm" type="submit">Marquer traitée</button>
                            </form>
                        @else
                            <span class="pill c-ok">Traitée le {{ $r->processed_at?->format('d/m/Y') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $requests->links() }}
@endif
@endsection
