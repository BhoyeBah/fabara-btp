@extends('layouts.app')

@section('title', 'Modernisez votre vieille maison')
@section('description', 'Rénovation de vieilles maisons à Conakry : façade, murs, plafonds, carrelage, étanchéité. Visite et devis gratuits. FABARA, '.$site['promise'].'.')

@section('content')
@php($first = $pairs[0] ?? null)

<section class="reno-hero">
    <div class="wrap">
        <div class="hero-copy">
            <span class="eyebrow" style="color:var(--gold)">Rénovation de maisons anciennes · Conakry</span>
            <span class="stamp">{{ $site['promise'] }}</span>
            <h1>Modernisez votre <em>vieille maison</em></h1>
            <p class="hero-lead">Votre maison a vieilli mais ses murs sont solides ? Pas besoin de démolir ni de reconstruire. FABARA lui redonne une façade moderne, un intérieur lumineux et des finitions haut de gamme, à un prix adapté à votre budget.</p>
            <div class="hero-cta">
                <a class="btn btn-gold" href="#devis">Demander une visite gratuite</a>
                <a class="btn btn-wa" data-wa="Bonjour FABARA, je veux moderniser ma maison. Je vous envoie quelques photos." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener"><svg><use href="#i-wa"/></svg>Envoyer des photos</a>
            </div>
            <div class="chips"><span class="chip">Visite et métré gratuits</span><span class="chip">Rénovation par étapes</span><span class="chip">Devis poste par poste</span></div>
        </div>
        @if ($first)
            @include('partials.before-after', ['pair' => $first, 'id' => 'ba-hero'])
        @endif
    </div>
</section>

<section class="block">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Ce que nous transformons</span><h2>Du vieux au moderne, poste par poste</h2><p>Voici les problèmes que nous rencontrons le plus souvent dans les maisons anciennes, et ce que nous en faisons.</p></div>
        <div class="pb">
            <div class="pb-item"><span class="pb-was">Façade fissurée, noircie par la pluie</span><span class="pb-now">Façade neuve</span><span class="muted">Reprise des fissures, enduit, peinture anti-humidité ou habillage pierre.</span></div>
            <div class="pb-item"><span class="pb-was">Petites fenêtres, pièces sombres</span><span class="pb-now">Grandes ouvertures</span><span class="muted">Agrandissement des baies, menuiseries alu, garde-corps en verre.</span></div>
            <div class="pb-item"><span class="pb-was">Plafond taché, une seule ampoule</span><span class="pb-now">Plafond lumineux</span><span class="muted">Faux plafond en plâtre, corniches, bandes LED et spots encastrés.</span></div>
            <div class="pb-item"><span class="pb-was">Vieux carrelage cassé ou démodé</span><span class="pb-now">Sols modernes</span><span class="muted">Grands formats, effet marbre ou bois, plinthes assorties.</span></div>
            <div class="pb-item"><span class="pb-was">Cuisine et salle de bain usées</span><span class="pb-now">Pièces d'eau refaites</span><span class="muted">Faïence, carrelage, plan de travail et finitions à neuf.</span></div>
            <div class="pb-item"><span class="pb-was">Toit-terrasse qui fuit</span><span class="pb-now">Étanchéité refaite</span><span class="muted">Fini les infiltrations et les murs qui moisissent.</span></div>
        </div>
    </div>
</section>

<section class="block band-navy" id="avant-apres">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Avant / Après</span><h2>Faites glisser pour voir la différence</h2><p>Déplacez la poignée dorée vers la gauche ou la droite pour comparer.</p></div>
        <div class="ba-grid">
            @foreach ($pairs as $i => $pair)
                @include('partials.before-after', ['pair' => $pair, 'id' => 'ba-'.$i])
            @endforeach
        </div>
    </div>
</section>

<section class="block">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Comment ça se passe</span><h2>Une rénovation à votre rythme</h2><p>Vous pouvez tout faire en une fois, ou commencer par la façade ou le salon et continuer plus tard. Chaque étape a son propre devis.</p></div>
        <div class="steps steps-light">
            <div class="step"><h3>Photos</h3><p class="muted">Envoyez 3 ou 4 photos de la maison sur WhatsApp ou avec le formulaire.</p></div>
            <div class="step"><h3>Visite gratuite</h3><p class="muted">Un technicien vérifie la structure, mesure et vous conseille.</p></div>
            <div class="step"><h3>Devis par étapes</h3><p class="muted">Façade, intérieur, sols… chaque poste chiffré séparément.</p></div>
            <div class="step"><h3>Travaux</h3><p class="muted">Chantier suivi par un chef d'équipe, photos d'avancement.</p></div>
            <div class="step"><h3>Remise des clés</h3><p class="muted">Visite finale ensemble, retouches et nettoyage.</p></div>
        </div>
        <figure class="strip" style="margin-top:36px">
            <img src="{{ asset('images/renovation/etapes.jpg') }}" alt="Étapes d'une rénovation de façade : maison d'origine, dépose, nouvelle structure, maison terminée" loading="lazy">
            <figcaption>Exemple d'étapes d'une rénovation de façade, de la maison d'origine à la maison terminée (illustration).</figcaption>
        </figure>
    </div>
</section>

<section class="block" id="devis" style="padding-top:0">
    <div class="wrap devis">
        <div class="devis-side">
            <div class="head" style="margin-bottom:0"><span class="eyebrow">Visite gratuite</span><h2>Parlez-nous de votre maison</h2><p>Décrivez l'état actuel et ce que vous aimeriez. Ajoutez des photos : c'est le meilleur moyen d'obtenir un premier avis rapidement.</p></div>
            <div class="contact-card">
                <span class="eyebrow">WhatsApp et téléphone</span>
                <span class="num">{{ $site['phone_display'] }}</span>
                <div class="row">
                    <a class="btn btn-wa btn-sm" data-wa="Bonjour FABARA, je veux moderniser ma maison." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener"><svg><use href="#i-wa"/></svg>Écrire sur WhatsApp</a>
                    <button class="btn btn-ghost btn-sm" type="button" data-copy="{{ $site['phone_display'] }}">Copier le numéro</button>
                </div>
            </div>
        </div>
        @include('partials.devis-box', [
            'suite' => 'renovation',
            'preset' => ['property_type' => 'Vieille maison à rénover', 'works' => ['Rénovation / modernisation']],
            'submitLabel' => 'Demander ma visite gratuite',
        ])
    </div>
</section>

<section class="block" style="padding-top:0">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Questions fréquentes</span><h2>Rénover plutôt que reconstruire</h2></div>
        <div class="faq">
            <details><summary>Ma maison est très ancienne, peut-on vraiment la rénover ?</summary><p>Dans la plupart des cas, oui. Lors de la visite, nous vérifions l'état des murs, de la toiture et des fondations. Si une partie doit être reprise ou renforcée, nous vous le disons avant tout devis.</p></details>
            <details><summary>Est-ce moins cher que de reconstruire ?</summary><p>En général oui, car on garde la structure existante. Le devis détaillé vous permet de comparer et de choisir ce que vous faites en premier.</p></details>
            <details><summary>Peut-on habiter la maison pendant les travaux ?</summary><p>Souvent, oui, surtout si les travaux se font par étapes (façade d'abord, puis pièce par pièce). Nous organisons le chantier avec vous.</p></details>
            <details><summary>Combien de temps durent les travaux ?</summary><p>Cela dépend de la surface et des postes choisis. Le planning est écrit dans le devis, avant le début du chantier.</p></details>
        </div>
    </div>
</section>
@endsection
