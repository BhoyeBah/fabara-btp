@extends('layouts.app')

@section('content')
<div style="background:var(--surface);border-bottom:1px solid var(--line)">
    <div class="wrap" style="display:flex;flex-wrap:wrap;gap:6px 18px;align-items:center;padding-block:12px">
        <b style="font:700 18px/1 var(--f-display);text-transform:uppercase">Mon espace</b>
        <nav style="display:flex;flex-wrap:wrap;gap:4px 14px;font-weight:600;font-size:15px" aria-label="Espace client">
            <a href="{{ route('client.dashboard') }}" @if(request()->routeIs('client.dashboard')) aria-current="page" style="color:var(--gold-ink)" @endif>Mes demandes</a>
            <a href="{{ route('client.devis.create') }}" @if(request()->routeIs('client.devis.create')) aria-current="page" style="color:var(--gold-ink)" @endif>Nouvelle demande</a>
            <a href="{{ route('client.account') }}" @if(request()->routeIs('client.account')) aria-current="page" style="color:var(--gold-ink)" @endif>Mon compte et mes données</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">
            @csrf
            <button class="btn btn-quiet btn-sm" type="submit">Déconnexion</button>
        </form>
    </div>
</div>
<div class="wrap app-main">
    @yield('page')
</div>
@endsection
