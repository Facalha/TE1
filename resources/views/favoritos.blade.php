<!-- resources/views/favoritos/index.blade.php -->

<h1>Meus Favoritos</h1>

@foreach($favoritos as $favorito)
    <div>
        <p>{{ $favorito->capitulo->texto }}</p>
    </div>
@endforeach
