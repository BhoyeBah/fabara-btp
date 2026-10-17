@extends('layouts.admin')

@section('title', 'Réalisations')

@section('content')
<div class="page-title">
    <div>
        <h1>Réalisations</h1>
        <p>Les photos affichées dans la galerie du site. Ajoutez vos vrais chantiers : ce sont eux qui convainquent le plus.</p>
        <p>Ajoutez aussi une <b>photo « avant »</b> : la réalisation s'affiche alors en comparateur avant / après sur l'accueil et sur la page Rénovation, à la place des exemples.</p>
    </div>
</div>

<form class="panel" method="POST" action="{{ route('admin.realisations.store') }}" enctype="multipart/form-data" style="max-width:820px">
    @csrf
    <h2>Ajouter une réalisation</h2>
    <div class="grid2">
        <label class="f" for="r-title">Titre<input id="r-title" name="title" required maxlength="120" value="{{ old('title') }}" placeholder="Ex. Salon rénové à Kipé"></label>
        <label class="f" for="r-cat">Catégorie
            <select id="r-cat" name="category">
                @foreach (config('fabara.realisation_categories') as $c)<option @selected(old('category') === $c)>{{ $c }}</option>@endforeach
            </select>
        </label>
    </div>
    <label class="f" for="r-loc">Lieu <small>(facultatif)</small><input id="r-loc" name="location" maxlength="120" value="{{ old('location') }}" placeholder="Ex. Ratoma"></label>
    <div class="grid2">
        <label class="f" for="r-before">Photo AVANT les travaux <small>(facultatif)</small><input id="r-before" name="before_image" type="file" accept="image/jpeg,image/png,image/webp"></label>
        <label class="f" for="r-img">Photo APRÈS / résultat <small>(obligatoire, 8 Mo max)</small><input id="r-img" name="image" type="file" accept="image/jpeg,image/png,image/webp" required></label>
    </div>
    <p class="muted" style="font-size:14px">Pour un bel avant / après, prenez les deux photos du même endroit et sous le même angle.</p>
    <label class="check" for="r-feat"><input id="r-feat" type="checkbox" name="is_featured" value="1"><span>Mettre en avant (affiché en premier sur l'accueil)</span></label>
    <label class="f" for="r-desc">Description <small>(facultatif)</small><textarea id="r-desc" name="description" maxlength="500" placeholder="Ex. Faux plafond avec LED et carrelage 60×60.">{{ old('description') }}</textarea></label>
    <p class="muted" style="font-size:14px">Avant de publier la photo d'un client ou de sa maison, demandez-lui son accord. Évitez les visages et les numéros de rue lisibles.</p>
    <button class="btn btn-gold" type="submit" style="justify-self:start">Publier sur le site</button>
</form>

@if ($realisations->isEmpty())
    <div class="empty"><b>Aucune réalisation</b><span>Ajoutez votre première photo de chantier ci-dessus.</span></div>
@else
    <div class="adm-gal">
        @foreach ($realisations as $r)
            <figure style="position:relative">
                @if ($r->hasBefore())
                    <div class="ba" data-ba style="--pos:50%;border-radius:0">
                        <img class="ba-after" src="{{ $r->imageUrl() }}" alt="Après : {{ $r->title }}" loading="lazy">
                        <img class="ba-before" src="{{ $r->beforeImageUrl() }}" alt="Avant : {{ $r->title }}" loading="lazy">
                        <span class="ba-tag ba-tag-before">Avant</span><span class="ba-tag ba-tag-after">Après</span>
                        <span class="ba-handle" aria-hidden="true"><span></span></span>
                        <input class="ba-range" type="range" min="0" max="100" value="50" aria-label="Comparer avant et après">
                    </div>
                @else
                    <img src="{{ $r->imageUrl() }}" alt="{{ $r->title }}" loading="lazy">
                @endif
                <figcaption>
                    <form method="POST" action="{{ route('admin.realisations.update', $r) }}" enctype="multipart/form-data" style="display:grid;gap:8px">
                        @csrf @method('PUT')
                        <input name="title" value="{{ $r->title }}" required maxlength="120" aria-label="Titre">
                        <select name="category" aria-label="Catégorie">
                            @foreach (config('fabara.realisation_categories') as $c)<option @selected($r->category === $c)>{{ $c }}</option>@endforeach
                        </select>
                        <input name="location" value="{{ $r->location }}" maxlength="120" placeholder="Lieu" aria-label="Lieu">
                        <textarea name="description" maxlength="500" placeholder="Description" aria-label="Description" style="min-height:60px">{{ $r->description }}</textarea>
                        <label class="check"><input type="checkbox" name="is_published" value="1" @checked($r->is_published)><span>Visible sur le site</span></label>
                        <label class="check"><input type="checkbox" name="is_featured" value="1" @checked($r->is_featured)><span>Mise en avant</span></label>
                        <label class="f" style="font-weight:400">Remplacer la photo après<input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
                        <label class="f" style="font-weight:400">{{ $r->hasBefore() ? 'Remplacer la photo avant' : 'Ajouter une photo avant' }}<input type="file" name="before_image" accept="image/jpeg,image/png,image/webp"></label>
                        @if ($r->hasBefore())
                            <label class="check"><input type="checkbox" name="remove_before" value="1"><span>Retirer la photo avant</span></label>
                        @endif
                        <button class="btn btn-quiet btn-sm" type="submit">Enregistrer</button>
                    </form>
                    <form method="POST" action="{{ route('admin.realisations.destroy', $r) }}" data-confirm="Retirer « {{ $r->title }} » du site ?">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" type="submit" style="color:var(--bad);background:none;padding-inline:0">Supprimer</button>
                    </form>
                </figcaption>
            </figure>
        @endforeach
    </div>
@endif
@endsection
