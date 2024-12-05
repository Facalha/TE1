<?php

// app/Http/Controllers/BibliaController.php

namespace App\Http\Controllers;

use App\Models\Testamento;
use App\Models\Capitulo;
use Illuminate\Http\Request;

class BibliaController extends Controller
{
    public function index()
    {
        // Exibir todos os testamentos
        $testamentos = Testamento::all();
        return view('biblia.index', compact('testamentos'));
    }

    public function livros($testamentoId)
    {
        // Exibir livros (testamentos)
        $testamento = Testamento::find($testamentoId);
        $capitulos = Capitulo::where('testamento_id', $testamentoId)->get();
        return view('biblia.livros', compact('testamento', 'capitulos'));
    }

    public function buscar(Request $request)
    {
        $termo = $request->input('termo');
        $capitulos = Capitulo::where('texto', 'LIKE', "%$termo%")
            ->orWhereHas('testamento', function ($query) use ($termo) {
                $query->where('nome', 'LIKE', "%$termo%")
                      ->orWhere('abrev', 'LIKE', "%$termo%");
            })->get();
        return view('biblia.buscar', compact('capitulos'));
    }
}

