<footer>
    <div class="wrap">
        <div>
            <a class="brand" href="{{ route('home') }}" style="display:flex;margin-bottom:12px"><svg><use href="#logo"/></svg><span><b>FABARA</b><small>Bâti – Finition – TP</small></span></a>
            <p style="font:700 20px/1.2 var(--f-display);text-transform:uppercase;color:var(--gold);margin-bottom:8px">{{ $site['slogan'] }}.<br>{{ $site['promise'] }}.</p>
            <p>Peinture, carrelage, plafonds, façades, rénovation, bâtiment et travaux publics à Conakry, Guinée.</p>
        </div>
        <div>
            <h4>Services</h4>
            @foreach (\App\Models\Service::published()->take(6) as $svc)
                <a href="{{ route('home') }}#service-{{ $svc->id }}">{{ $svc->title }}</a>
            @endforeach
            <a href="{{ route('renovation') }}">Rénovation de vieilles maisons</a>
        </div>
        <div>
            <h4>Contact</h4>
            <span class="mono" style="color:var(--on-navy);user-select:all">{{ $site['phone_display'] }}</span>
            @if ($site['email'])<a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>@endif
            <a href="{{ route('home') }}#devis">Demander un devis</a>
            @guest
                <a href="{{ route('login') }}">Connexion</a>
                <a href="{{ route('register') }}">Inscription</a>
            @endguest
        </div>
        <div class="fine">
            <span>© {{ date('Y') }} {{ config('fabara.company.name') }} · {{ $site['address'] }}</span>
            <span>
                <a href="{{ route('privacy') }}" style="display:inline">Confidentialité</a> ·
                <a href="{{ route('terms') }}" style="display:inline">Conditions d'utilisation</a> ·
                <a href="{{ route('deletion') }}" style="display:inline">Supprimer mon compte</a>
            </span>
        </div>
    </div>
</footer>
