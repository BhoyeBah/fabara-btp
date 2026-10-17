{{-- Champs du formulaire de devis, partagés entre l'accueil et l'espace client.
     Variables : $user (client connecté ou null), $askConsent (bool), $preset (facultatif : valeurs pré-remplies) --}}
@php($preset = $preset ?? [])
<div class="grid2">
    <label class="f" for="d-name">Nom complet
        <input id="d-name" name="name" autocomplete="name" required maxlength="120" value="{{ old('name', $user?->name) }}" placeholder="Ex. Mamadou Diallo">
    </label>
    <label class="f" for="d-phone">Téléphone / WhatsApp
        <input id="d-phone" name="phone" type="tel" autocomplete="tel" required maxlength="30" value="{{ old('phone', $user?->phone) }}" placeholder="+224 6xx xx xx xx">
    </label>
</div>
<label class="f" for="d-email">E-mail <small>(facultatif)</small>
    <input id="d-email" name="email" type="email" autocomplete="email" maxlength="190" value="{{ old('email', $user?->email) }}">
</label>
<div class="grid2">
    <label class="f" for="d-commune">Lieu du chantier
        <select id="d-commune" name="commune" required>
            @foreach (config('fabara.communes') as $c)
                <option @selected(old('commune', 'Ratoma') === $c)>{{ $c }}</option>
            @endforeach
        </select>
    </label>
    <label class="f" for="d-type">Type de bien
        <select id="d-type" name="property_type" required>
            @foreach (config('fabara.property_types') as $t)
                <option @selected(old('property_type', $preset['property_type'] ?? null) === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </label>
</div>
<fieldset style="border:0;padding:0;margin:0;display:grid;gap:8px">
    <legend style="font-size:14px;font-weight:600;margin-bottom:8px">Travaux souhaités</legend>
    <div class="checks">
        @foreach (\App\Models\Service::quoteChoices() as $w)
            <label><input type="checkbox" name="works[]" value="{{ $w }}" @checked(in_array($w, old('works', $preset['works'] ?? []), true))>{{ $w }}</label>
        @endforeach
    </div>
</fieldset>
<div class="grid2">
    <label class="f" for="d-surface">Surface approximative <small>(facultatif)</small>
        <div class="unit"><input id="d-surface" name="surface" inputmode="decimal" data-decimal value="{{ old('surface') }}" placeholder="120"><span>m²</span></div>
    </label>
    <label class="f" for="d-start">Démarrage souhaité
        <select id="d-start" name="start_when">
            @foreach (config('fabara.start_options') as $o)
                <option @selected(old('start_when', 'Dans le mois') === $o)>{{ $o }}</option>
            @endforeach
        </select>
    </label>
</div>
<label class="f" for="d-msg">Votre projet
    <textarea id="d-msg" name="message" maxlength="2000" placeholder="Ex. Vieille maison à Lambanyi : reprise des fissures de la façade, carrelage du salon et faux plafond avec LED.">{{ old('message') }}</textarea>
</label>
<label class="f" for="d-photos">Photos de votre maison ou du chantier <small>(facultatif, 5 maximum, 5 Mo chacune)</small>
    <input id="d-photos" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple data-preview="d-photos-preview">
</label>
<div class="photos" id="d-photos-preview"></div>
@if ($askConsent)
    <label class="check" for="d-consent">
        <input id="d-consent" type="checkbox" name="consent" value="1" required @checked(old('consent'))>
        <span>J'accepte que FABARA utilise ces informations pour me recontacter et établir mon devis, comme expliqué dans la <a href="{{ route('privacy') }}" target="_blank">politique de confidentialité</a>. Elles ne sont ni vendues ni partagées à des fins publicitaires.</span>
    </label>
@endif
