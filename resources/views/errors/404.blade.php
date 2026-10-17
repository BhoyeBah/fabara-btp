@extends('layouts.app')

@section('title', 'Page introuvable')

@section('content')
<div class="wrap">
    <div class="err-page">
        <h1>404</h1>
        <h2 style="text-transform:uppercase">Page introuvable</h2>
        <p class="muted">Cette page n'existe pas ou a été déplacée.</p>
        <a class="btn btn-gold" href="{{ route('home') }}">Retour à l'accueil</a>
    </div>
</div>
@endsection
