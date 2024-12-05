<!-- resources/views/biblia/livros.blade.php -->

<h1>{{ $testamento->nome }} - Capitulos</h1>

@foreach($capitulos as $capitulo)
    <div>
        <p>Capítulo {{ $capitulo->numero }}</p>
        <p>{{ $capitulo->texto }}</p>
        <form action="{{ route('favoritos.adicionar', $capitulo->id) }}" method="POST">
            @csrf
            <button type="submit">Favoritar</button>
        </form>
    </div>
@endforeach
