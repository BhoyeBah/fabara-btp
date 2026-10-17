<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') · @endif{{ config('fabara.company.name') }}</title>
    <meta name="description" content="@yield('description', 'FABARA Bâti – Finition – TP à Conakry : peinture, carrelage, plafonds, façades, rénovation de vieilles maisons. '.$site['promise'].'.')">
    <meta name="theme-color" content="#0A1730">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    {{-- Bunny Fonts : hébergeur européen, sans pistage des visiteurs (pas de Google Fonts). --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=barlow:400,500,600,700|barlow-condensed:500,600,700|ibm-plex-mono:400,500&display=swap">
    <link rel="stylesheet" href="{{ asset('css/fabara.css') }}?v=1">
    @stack('head')
</head>
<body data-whatsapp="{{ $site['whatsapp'] }}">
@include('partials.icons')

@if (!empty($site['announcement']))
    <div class="announce"><div class="wrap">{{ $site['announcement'] }}</div></div>
@endif

@include('partials.header')

@include('partials.flash')

<main id="contenu">
    @yield('content')
</main>

@include('partials.footer')

<a class="wa-float" data-wa="Bonjour FABARA, je souhaite des informations sur vos travaux." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener" aria-label="Écrire à FABARA sur WhatsApp"><svg><use href="#i-wa"/></svg></a>

<script src="{{ asset('js/fabara.js') }}?v=1" defer></script>
@stack('scripts')
</body>
</html>
