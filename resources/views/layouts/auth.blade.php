@extends('layouts.app')

@section('content')
<div class="auth">
    <aside class="auth-side">
        <div class="bg" style="background-image:url('{{ asset('images/hero.jpg') }}')"></div>
        <div>
            <span class="stamp">{{ $site['promise'] }}</span>
            @php($w = explode(' ', $site['slogan']))
            <h2>{{ implode(' ', array_slice($w, 0, max(1, count($w) - 4))) }} <em>{{ implode(' ', array_slice($w, max(1, count($w) - 4))) }}</em></h2>
            <p>Votre espace client FABARA : suivez vos demandes de devis, envoyez des photos et échangez avec notre équipe.</p>
        </div>
    </aside>
    <div class="auth-main">
        <div class="auth-card">
            @yield('form')
        </div>
    </div>
</div>
@endsection
