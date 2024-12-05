<?php

// app/Http/Controllers/FavoritoController.php

namespace App\Http\Controllers;

use App\Models\Capitulo;
use App\Models\Favorito;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    public function adicionarFavorito($capituloId)
    {
        $capitulo = Capitulo::find($capituloId);
        $favorito = new Favorito();
        $favorito->capitulo_id = $capitulo->id;
        $favorito->save();

        return back();
    }

    public function listarFavoritos()
    {
        $favoritos = Favorito::with('capitulo')->get();
        return view('favoritos.index', compact('favoritos'));
    }
}

