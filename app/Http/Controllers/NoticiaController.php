<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticia;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        // $noticias = Noticia::all();
        $filters = $request->only(['title', 'description']);
        $noticias = Noticia::filter( $filters )->paginante(10)->withQueryString();
        return view('dashboard', compact('noticias'));
    }
    public function home()
    {
        $noticias = Noticia::all();
        return view('home', compact('noticias'));
    }

    public function create()
    {
        return view('noticias.create');
        }

   
    public function store(Request $request)
    {
        $request->validate([
            'titulo'=> 'required|string|max:255',
            'descricao'=> 'required|string',
            'arquivo'=> 'required|file|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $noticia= Noticia::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,

        ]);

        $noticia->storeArquivo($request->file('arquivo'));

        return redirect()->route('dashboard')->with('success', 'Notícia criada com successo');
    }

    public function show(Noticia $noticia)
    {
        return view('noticias.show', compact('noticia'));
    }

    public function edit(Noticia $noticia)
    {
        return view('noticias.edit', compact('noticia'));
    }

    public function update(Request $request, Noticia $noticia)
    {
        $request->validate([
            'titulo'=> 'required|string|max:255',
            'descricao'=> 'required|string',
            'arquivo'=> 'required|file|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $noticia->titulo = $request->titulo;
        $noticia->descricao = $request->descricao;

        if($request->hasFile('arquivo')){
            $noticia->storeArquivo($request->file('arquivo'));
        }

        $noticia->save();

        return redirect()->route('dashboard')->with('success', 'Notícia atualizada com successo');
    }

    
    public function destroy(Noticia $noticia)
    {
        $noticia->delete();

        return redirect()->route('dashboard')->with('success', 'Notícia deletada com successo');
    }
}
