@extends('layouts.app')

@section('content')
<section class="hero" id="accueil">
    <div class="hero-img" style="background-image:url('{{ asset('images/hero.jpg') }}')" role="img" aria-label="Chef de chantier FABARA présentant une villa terminée à un couple de clients"></div>
    <div class="wrap">
        <div class="hero-copy">
            <span class="eyebrow">Conakry · Guinée · Particuliers, entreprises, promoteurs</span>
            <span class="stamp">{{ $site['promise'] }}</span>
            @php
                $words = explode(' ', $site['slogan']);
                // Les 4 derniers mots du slogan sont mis en valeur en doré.
                $cut = count($words) > 5 ? count($words) - 4 : max(1, (int) ceil(count($words) / 2));
            @endphp
            <h1>{{ implode(' ', array_slice($words, 0, $cut)) }} <em>{{ implode(' ', array_slice($words, $cut)) }}</em></h1>
            <p class="hero-lead">{{ $site['hero_text'] }}</p>
            <div class="hero-cta">
                <a class="btn btn-gold" href="#devis">Demander un devis gratuit</a>
                <a class="btn btn-wa" data-wa="Bonjour FABARA, je souhaite des informations sur vos travaux." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener"><svg><use href="#i-wa"/></svg>WhatsApp</a>
                <a class="btn btn-ghost" href="{{ route('renovation') }}">Moderniser ma vieille maison</a>
            </div>
            <div class="chips"><span class="chip">{{ $site['phone_display'] }}</span><span class="chip">Visite et métré sur site</span><span class="chip">Devis détaillé</span></div>
        </div>
    </div>
</section>

<div class="values"><div class="wrap">
    <div class="value"><svg><use href="#i-check"/></svg><div><b>Qualité</b><span>Matériaux choisis avec vous</span></div></div>
    <div class="value"><svg><use href="#i-gear"/></svg><div><b>Professionnalisme</b><span>Équipes formées et encadrées</span></div></div>
    <div class="value"><svg><use href="#i-clock"/></svg><div><b>Respect des délais</b><span>Planning écrit avant le début</span></div></div>
    <div class="value"><svg><use href="#i-people"/></svg><div><b>Satisfaction client</b><span>Réception des travaux ensemble</span></div></div>
</div></div>

<section class="block luxe" id="luxe">
    <div class="wrap">
        <div class="luxe-grid">
            <div class="luxe-head">
                <span class="eyebrow">Notre promesse</span>
                <h2>{{ $site['promise'] }}</h2>
                <p>Un plafond lumineux, un carrelage grand format, une façade en pierre : ces finitions ne sont pas réservées aux grands budgets. Nous adaptons les matériaux et la méthode à ce que vous pouvez investir, sans sacrifier le rendu.</p>
                <a class="btn btn-gold" href="#calculateur">Estimer mon projet</a>
            </div>
            <div class="luxe-pts">
                <div class="luxe-pt"><b>Vous choisissez la gamme</b><span>Plusieurs choix de matériaux pour chaque poste, du plus économique au plus prestigieux. Le devis montre l'écart de prix entre chaque option.</span></div>
                <div class="luxe-pt"><b>Un devis poste par poste</b><span>Surfaces, quantités, fournitures et main-d'œuvre sont détaillés. Le prix signé est le prix payé.</span></div>
                <div class="luxe-pt"><b>Visite et métré gratuits</b><span>Un technicien se déplace à Conakry et environs pour mesurer et vous conseiller avant tout engagement.</span></div>
            </div>
        </div>
    </div>
</section>

<section class="block band-navy renov" id="renovation">
    <div class="wrap renov-grid">
        <div class="renov-copy">
            <span class="eyebrow">Rénovation de maisons anciennes</span>
            <h2>Modernisez votre vieille maison</h2>
            <p class="renov-lead">Fissures, peinture qui s'écaille, vieux carrelage, plafond taché par l'humidité ? Pas besoin de démolir. FABARA transforme votre maison en un intérieur moderne et lumineux, pièce par pièce ou en une seule fois, selon votre budget.</p>
            <ul class="renov-list">
                <li><b>Façade</b><span>Reprise des fissures, enduit neuf, peinture ou habillage pierre.</span></li>
                <li><b>Murs et plafonds</b><span>Traitement de l'humidité, faux plafond avec éclairage LED.</span></li>
                <li><b>Sols</b><span>Remplacement du vieux carrelage par des grands formats modernes.</span></li>
                <li><b>Cuisine et salle de bain</b><span>Faïence, carrelage et finitions refaits à neuf.</span></li>
                <li><b>Toiture-terrasse</b><span>Étanchéité pour stopper les infiltrations.</span></li>
            </ul>
            <p class="renov-step"><b>Rénovez par étapes.</b> Commencez par la façade ou le salon, puis continuez quand vous êtes prêt. Chaque étape a son propre devis.</p>
            <div class="hero-cta">
                <a class="btn btn-wa" data-wa="Bonjour FABARA, je veux moderniser ma maison. Je vous envoie quelques photos." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener"><svg><use href="#i-wa"/></svg>Envoyer des photos de ma maison</a>
                <a class="btn btn-gold" href="{{ route('renovation') }}">Voir les avant / après</a>
            </div>
            <small class="renov-note">Envoyez 3 ou 4 photos sur WhatsApp ou avec le formulaire : nous vous disons ce qui est possible et à quel prix.</small>
        </div>
        @if (!empty($pairs))
            <div style="display:grid;gap:12px;min-width:0">
                @include('partials.before-after', ['pair' => $pairs[0], 'id' => 'ba-home', 'tall' => true])
                <a href="{{ route('renovation') }}" style="color:var(--gold);font-weight:600">Voir toutes les transformations avant / après →</a>
            </div>
        @endif
    </div>
</section>

<section class="block" id="services">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Nos métiers</span><h2>Du gros œuvre à la dernière couche</h2><p>Chaque chantier est suivi par un chef d'équipe FABARA, de la préparation des supports jusqu'au nettoyage final.</p></div>
        @php
            $cards = $services->filter(fn ($s) => $s->image_path);
            $lines = $services->reject(fn ($s) => $s->image_path);
        @endphp
        @if ($services->isEmpty())
            <div class="empty"><b>Nos services arrivent bientôt</b><span>Écrivez-nous sur WhatsApp pour décrire votre projet.</span></div>
        @endif
        @if ($cards->isNotEmpty())
            <div class="svc-grid">
                @foreach ($cards as $svc)
                    <article class="svc" id="service-{{ $svc->id }}">
                        <img src="{{ $svc->imageUrl() }}" alt="{{ $svc->image_alt ?: $svc->title }}" loading="lazy">
                        <div class="body">
                            <h3><i><svg><use href="#i-{{ $svc->icon }}"/></svg></i>{{ $svc->title }}</h3>
                            <p>{{ $svc->summary }}</p>
                            @if (!empty($svc->items))
                                <ul>@foreach ($svc->items as $it)<li>{{ $it }}</li>@endforeach</ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
        @if ($lines->isNotEmpty())
            <div class="svc-more">
                @foreach ($lines as $svc)
                    <div class="svc-line" id="service-{{ $svc->id }}"><i><svg><use href="#i-{{ $svc->icon }}"/></svg></i><div><h3>{{ $svc->title }}</h3><p>{{ $svc->summary }}</p>
                        @if (!empty($svc->items))<p style="margin-top:6px">{{ implode(' · ', $svc->items) }}</p>@endif
                    </div></div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="block band-navy" id="methode">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Notre méthode</span><h2>Comment se déroule votre chantier</h2><p>Les mêmes cinq étapes pour une chambre à repeindre ou une villa à finir.</p></div>
        <div class="steps">
            <div class="step"><h3>Contact</h3><p>Vous décrivez votre projet par WhatsApp, téléphone ou avec le formulaire.</p></div>
            <div class="step"><h3>Visite et métré</h3><p>Un technicien se déplace, mesure les surfaces et contrôle l'état des supports.</p></div>
            <div class="step"><h3>Devis détaillé</h3><p>Quantités, matériaux, main d'œuvre et délai, poste par poste.</p></div>
            <div class="step"><h3>Travaux</h3><p>Chantier protégé, suivi par un chef d'équipe, photos d'avancement.</p></div>
            <div class="step"><h3>Réception</h3><p>Visite finale ensemble, retouches, nettoyage et remise des clés.</p></div>
        </div>
    </div>
</section>

<section class="block" id="realisations">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Réalisations</span><h2>Nos chantiers en images</h2><p>Finitions intérieures, façades et villas livrées par les équipes FABARA.</p></div>
        @if ($realisations->isEmpty())
            <div class="empty"><b>Les photos de chantier arrivent bientôt</b><span>Demandez-nous des exemples sur WhatsApp.</span></div>
        @else
            <div class="filters" id="galFilters" role="group" aria-label="Filtrer les réalisations">
                <button type="button" class="filter" data-cat="Tout" aria-pressed="true">Tout</button>
                @foreach ($categories as $cat)
                    <button type="button" class="filter" data-cat="{{ $cat }}" aria-pressed="false">{{ $cat }}</button>
                @endforeach
            </div>
            <div class="gal" id="gallery">
                @foreach ($realisations as $r)
                    <figure data-cat="{{ $r->category }}" @class(['wide' => $loop->first && ! $r->hasBefore()])>
                        @if ($r->hasBefore())
                            <div class="ba" data-ba style="--pos:50%;border-radius:0">
                                <img class="ba-after" src="{{ $r->imageUrl() }}" alt="Après : {{ $r->title }}" loading="lazy">
                                <img class="ba-before" src="{{ $r->beforeImageUrl() }}" alt="Avant : {{ $r->title }}" loading="lazy">
                                <span class="ba-tag ba-tag-before">Avant</span><span class="ba-tag ba-tag-after">Après</span>
                                <span class="ba-handle" aria-hidden="true"><span></span></span>
                                <input class="ba-range" type="range" min="0" max="100" value="50" aria-label="Comparer avant et après : {{ $r->title }}">
                            </div>
                        @else
                            <img src="{{ $r->imageUrl() }}" alt="{{ $r->title }} — {{ $r->category }}" loading="lazy">
                        @endif
                        <figcaption>
                            <span class="tag">{{ $r->category }}</span>
                            <b>{{ $r->title }}</b>
                            @if ($r->location || $r->description)
                                <span>{{ collect([$r->location, $r->description])->filter()->implode(' · ') }}</span>
                            @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="block" id="calculateur" style="padding-top:0">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Calculateur de chantier</span><h2>Estimez vos quantités en une minute</h2><p>Entrez les dimensions de la pièce ou de la façade. Le calcul donne les surfaces et les quantités de matériaux à prévoir, puis vous pouvez les joindre à votre demande de devis.</p></div>
        <div class="calc">
            <div class="calc-in">
                <div class="seg" id="calcType" role="group" aria-label="Type de travaux">
                    <button type="button" data-t="peinture" aria-pressed="true">Peinture murs</button>
                    <button type="button" data-t="carrelage" aria-pressed="false">Carrelage sol</button>
                    <button type="button" data-t="plafond" aria-pressed="false">Faux plafond</button>
                    <button type="button" data-t="facade" aria-pressed="false">Façade</button>
                </div>
                <div class="fields" id="calcFields"></div>
            </div>
            <div class="calc-out" id="calcOut" aria-live="polite"></div>
        </div>
    </div>
</section>

<section class="block" id="devis" style="padding-top:0">
    <div class="wrap devis">
        <div class="devis-side" id="contact">
            <div class="head" style="margin-bottom:0"><span class="eyebrow">Devis gratuit</span><h2>Parlez-nous de votre projet</h2><p>Depuis votre compte client gratuit, décrivez votre projet et ajoutez vos photos : votre demande arrive directement chez FABARA et vous suivez son avancement. Nous vous rappelons pour fixer la visite.</p></div>
            <div class="contact-card">
                <span class="eyebrow">WhatsApp et téléphone</span>
                <span class="num">{{ $site['phone_display'] }}</span>
                <div class="row">
                    <a class="btn btn-wa btn-sm" data-wa="Bonjour FABARA, je souhaite un devis." href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank" rel="noopener"><svg><use href="#i-wa"/></svg>Écrire sur WhatsApp</a>
                    <button class="btn btn-ghost btn-sm" type="button" data-copy="{{ $site['phone_display'] }}">Copier le numéro</button>
                </div>
            </div>
            <div class="mini">
                <div><svg><use href="#i-pin"/></svg><span>{{ $site['address'] }} · Kaloum, Dixinn, Matam, Ratoma, Matoto, Dubréka, Coyah.</span></div>
                @if ($site['hours'])<div><svg><use href="#i-clock"/></svg><span>{{ $site['hours'] }}</span></div>@endif
                @guest
                    <div><svg><use href="#i-lock"/></svg><span>Avec un <a href="{{ route('register') }}">compte client</a>, vous suivez l'avancement de vos demandes et échangez avec FABARA.</span></div>
                @endguest
            </div>
        </div>
        @include('partials.devis-box', ['suite' => 'devis'])
    </div>
</section>

<section class="block" style="padding-top:0">
    <div class="wrap">
        <div class="head"><span class="eyebrow">Questions fréquentes</span><h2>Avant de commencer</h2></div>
        <div class="faq">
            <details><summary>Le devis est-il vraiment gratuit ?</summary><p>Oui. La visite de chantier, le métré et le devis détaillé ne vous engagent à rien.</p></details>
            <details><summary>Fournissez-vous les matériaux ?</summary><p>Nous pouvons fournir peinture, carreaux, plâtre et accessoires, ou travailler avec vos matériaux. Le devis distingue toujours fournitures et main d'œuvre.</p></details>
            <details><summary>Ma maison est ancienne, peut-on vraiment la moderniser ?</summary><p>Oui, dans la grande majorité des cas. Après la visite, nous vous indiquons ce qui peut être repris, ce qui doit être remplacé et dans quel ordre faire les travaux.</p></details>
            <details><summary>Travaillez-vous hors de Conakry ?</summary><p>Oui, à Dubréka, Coyah et dans les autres villes de Guinée selon la taille du chantier. Indiquez le lieu dans votre demande.</p></details>
            <details><summary>Que faites-vous de mes informations ?</summary><p>Elles servent uniquement à traiter votre demande. Elles ne sont ni vendues ni utilisées pour de la publicité. Tous les détails sont dans notre <a href="{{ route('privacy') }}">politique de confidentialité</a>.</p></details>
        </div>
    </div>
</section>
@endsection
