<!-- resources/views/biblia/buscar.blade.php -->

<h1>Buscar por Versículos ou Livros</h1>
<form action="{{ route('biblia.buscar') }}" method="GET">
    <input type="text" name="termo" placeholder="Buscar" />
    <button type="submit">Buscar</button>
</form>

@foreach($capitulos as $capitulo)
    <div>
        <p>Capítulo: {{ $capitulo->numero }}</p>
        <p>{{ $capitulo->texto }}</p>
    </div>
@endforeach
