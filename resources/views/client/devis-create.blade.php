@extends('layouts.client')

@section('title', 'Nouvelle demande')

@section('page')
<div class="page-title">
    <div>
        <p class="crumb"><a href="{{ route('client.dashboard') }}">Mes demandes</a> › Nouvelle demande</p>
        <h1>Nouvelle demande de devis</h1>
        <p>Plus vous donnez de détails et de photos, plus le devis sera précis.</p>
    </div>
</div>
<form class="card" id="devisForm" method="POST" action="{{ route('client.devis.store') }}" enctype="multipart/form-data" style="max-width:820px">
    @csrf
    @include('partials.devis-fields', ['user' => $user, 'askConsent' => false, 'preset' => $preset ?? []])
    <button class="btn btn-gold" type="submit" style="justify-self:start">Envoyer ma demande</button>
</form>
@endsection
