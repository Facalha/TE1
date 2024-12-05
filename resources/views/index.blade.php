<!-- resources/views/biblia/index.blade.php -->

<h1>Livros da Bíblia</h1>

@foreach($testamentos as $testamento)
    <h2>{{ $testamento->nome }} ({{ $testamento->periodo }})</h2>
    <a href="{{ route('biblia.livros', $testamento->id) }}">Ver Capitulos</a>
@endforeach
