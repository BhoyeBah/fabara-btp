@extends('layouts.admin')

@section('title', 'Services')

@section('content')
<div class="page-title">
    <div>
        <h1>Services</h1>
        <p>Les métiers affichés dans la section « Nos métiers » de l'accueil et dans le pied de page. Un service <b>avec photo</b> s'affiche en grande carte, un service <b>sans photo</b> en ligne sous les cartes.</p>
    </div>
</div>

<details class="panel" @if($errors->any() || $services->isEmpty()) open @endif style="max-width:860px">
    <summary style="cursor:pointer;font:700 22px/1 var(--f-display);text-transform:uppercase">+ Ajouter un service</summary>
    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" style="display:grid;gap:14px;margin-top:16px">
        @csrf
        <div class="grid2">
            <label class="f" for="s-title">Nom du service<input id="s-title" name="title" required maxlength="80" value="{{ old('title') }}" placeholder="Ex. Électricité bâtiment"></label>
            <label class="f" for="s-icon">Icône
                <select id="s-icon" name="icon">
                    @foreach ($icons as $key => $label)<option value="{{ $key }}" @selected(old('icon') === $key)>{{ $label }}</option>@endforeach
                </select>
            </label>
        </div>
        <label class="f" for="s-summary">Description courte<textarea id="s-summary" name="summary" required maxlength="300" style="min-height:70px" placeholder="Ex. Installation et mise aux normes de l'électricité de votre maison ou de vos bureaux.">{{ old('summary') }}</textarea></label>
        <label class="f" for="s-items">Points forts <small>(un par ligne, 6 maximum, facultatif)</small><textarea id="s-items" name="items" maxlength="1000" style="min-height:90px" placeholder="Tableaux électriques&#10;Prises et éclairage&#10;Mise à la terre">{{ old('items') }}</textarea></label>
        <div class="grid2">
            <label class="f" for="s-image">Photo <small>(facultatif — sans photo, le service s'affiche en ligne)</small><input id="s-image" name="image" type="file" accept="image/jpeg,image/png,image/webp"></label>
            <label class="f" for="s-alt">Description de la photo <small>(pour Google et les malvoyants)</small><input id="s-alt" name="image_alt" maxlength="190" value="{{ old('image_alt') }}" placeholder="Ex. Électricien FABARA installant un tableau"></label>
        </div>
        <label class="check"><input type="checkbox" name="is_published" value="1" checked><span>Visible sur le site</span></label>
        <label class="check"><input type="checkbox" name="in_quote_form" value="1" checked><span>Proposer ce service dans « Travaux souhaités » du formulaire de devis</span></label>
        <button class="btn btn-gold" type="submit" style="justify-self:start">Ajouter le service</button>
    </form>
</details>

@if ($services->isEmpty())
    <div class="empty"><b>Aucun service</b><span>Ajoutez votre premier service ci-dessus.</span></div>
@else
    <div class="list">
        @foreach ($services as $svc)
            <details class="panel" style="gap:12px">
                <summary style="cursor:pointer;display:flex;gap:14px;align-items:center;flex-wrap:wrap;list-style:none">
                    @if ($svc->imageUrl())
                        <img src="{{ $svc->imageUrl() }}" alt="" style="width:72px;height:54px;object-fit:cover;border-radius:6px;flex:none">
                    @else
                        <span style="width:72px;height:54px;border-radius:6px;background:var(--navy);color:var(--gold);display:grid;place-items:center;flex:none"><svg style="width:26px;height:26px"><use href="#i-{{ $svc->icon }}"/></svg></span>
                    @endif
                    <span style="display:grid;gap:2px;flex:1;min-width:180px">
                        <b style="font:700 20px/1.1 var(--f-display);text-transform:uppercase">{{ $svc->title }}</b>
                        <span class="muted" style="font-size:14px">{{ \Illuminate\Support\Str::limit($svc->summary, 90) }}</span>
                    </span>
                    <span class="tags">
                        @if ($svc->is_published)<span class="pill c-ok">Visible</span>@else<span class="pill c-muted">Masqué</span>@endif
                        <span class="pill c-info">{{ $svc->image_path ? 'Carte avec photo' : 'Ligne sans photo' }}</span>
                        @if ($svc->in_quote_form)<span class="pill c-gold">Dans le devis</span>@endif
                    </span>
                    <span class="lead-actions">
                        <form method="POST" action="{{ route('admin.services.move', [$svc, 'haut']) }}">@csrf<button class="btn btn-quiet btn-sm" type="submit" title="Monter" aria-label="Monter {{ $svc->title }}" @disabled($loop->first)>↑</button></form>
                        <form method="POST" action="{{ route('admin.services.move', [$svc, 'bas']) }}">@csrf<button class="btn btn-quiet btn-sm" type="submit" title="Descendre" aria-label="Descendre {{ $svc->title }}" @disabled($loop->last)>↓</button></form>
                        <span class="btn btn-gold btn-sm">Modifier</span>
                    </span>
                </summary>

                <form method="POST" action="{{ route('admin.services.update', $svc) }}" enctype="multipart/form-data" style="display:grid;gap:14px;padding-top:14px;border-top:1px dashed var(--line)">
                    @csrf @method('PUT')
                    <div class="grid2">
                        <label class="f">Nom du service<input name="title" required maxlength="80" value="{{ $svc->title }}"></label>
                        <label class="f">Icône
                            <select name="icon">
                                @foreach ($icons as $key => $label)<option value="{{ $key }}" @selected($svc->icon === $key)>{{ $label }}</option>@endforeach
                            </select>
                        </label>
                    </div>
                    <label class="f">Description courte<textarea name="summary" required maxlength="300" style="min-height:70px">{{ $svc->summary }}</textarea></label>
                    <label class="f">Points forts <small>(un par ligne, 6 maximum)</small><textarea name="items" maxlength="1000" style="min-height:90px">{{ implode("\n", $svc->items ?? []) }}</textarea></label>
                    <div class="grid2">
                        <label class="f">{{ $svc->image_path ? 'Remplacer la photo' : 'Ajouter une photo' }}<input name="image" type="file" accept="image/jpeg,image/png,image/webp"></label>
                        <label class="f">Description de la photo<input name="image_alt" maxlength="190" value="{{ $svc->image_alt }}"></label>
                    </div>
                    @if ($svc->image_path)
                        <label class="check"><input type="checkbox" name="remove_image" value="1"><span>Retirer la photo (le service s'affichera en ligne)</span></label>
                    @endif
                    <label class="check"><input type="checkbox" name="is_published" value="1" @checked($svc->is_published)><span>Visible sur le site</span></label>
                    <label class="check"><input type="checkbox" name="in_quote_form" value="1" @checked($svc->in_quote_form)><span>Proposer dans « Travaux souhaités » du formulaire de devis</span></label>
                    <div class="lead-actions">
                        <button class="btn btn-gold btn-sm" type="submit">Enregistrer</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.services.destroy', $svc) }}" data-confirm="Supprimer définitivement le service « {{ $svc->title }} » ? Vous pouvez aussi simplement le masquer.">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm" type="submit" style="color:var(--bad);background:none;padding-inline:0">Supprimer ce service</button>
                </form>
            </details>
        @endforeach
    </div>
@endif
@endsection
