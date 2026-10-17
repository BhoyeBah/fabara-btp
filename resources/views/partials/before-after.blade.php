{{-- Comparateur avant / après : faites glisser la poignée (souris, doigt ou flèches du clavier).
     Variables : $pair = ['title', 'before', 'after', 'caption', 'example'], $id (unique), $tall (bool, facultatif) --}}
<figure class="ba-fig">
    <div class="ba{{ !empty($tall) ? ' ba-tall' : '' }}" data-ba style="--pos:50%">
        <img class="ba-after" src="{{ $pair['after'] }}" alt="Après : {{ $pair['title'] }}" loading="lazy">
        <img class="ba-before" src="{{ $pair['before'] }}" alt="Avant : {{ $pair['title'] }}" loading="lazy">
        <span class="ba-tag ba-tag-before">Avant</span>
        <span class="ba-tag ba-tag-after">Après</span>
        <span class="ba-handle" aria-hidden="true"><span></span></span>
        <input class="ba-range" type="range" min="0" max="100" value="50" id="{{ $id }}" aria-label="Comparer avant et après : {{ $pair['title'] }}">
    </div>
    <figcaption>
        <b>{{ $pair['title'] }}</b>
        @if (!empty($pair['caption']))<span>{{ $pair['caption'] }}</span>@endif
        @if (!empty($pair['example']))<small class="ba-note">Exemple de transformation (illustration)</small>@endif
    </figcaption>
</figure>
