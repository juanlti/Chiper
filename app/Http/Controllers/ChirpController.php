<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return response('estoy en el metodo index');
        //return view('chirps.index');


        // La siguient sintaxis, envia a la vista, todos los chiprs de cada usuarios y con el usario.


        //dd( Chirp::with('user')->latest()->get());
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),


        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //realizo la verificacion de los inputs
        $validated = $request->validate([
            'message' => 'required|max:255|min:5'
        ]);
        //test, si se ejecuta, es afirmativo
        //dd($validated);

        //creo el chirp
        $chirp = Chirp::create([
            //auth() conoce los datos del usuario que esta realizando la peticion, id, name y etc
            // con auth()->id() obtengo el id del usuario que esta realizando la peticion
            'user_id' => auth()->id(),
            'message' => $validated['message']
        ]);

        return redirect()->route('chirps.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        //
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        //
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        //
        // re utilizo el metodo update, para verificar si el usuario que esta realizando la peticion, es el autor del chirp
        $this->authorize('update', $chirp);

        $chirp->delete();

        return redirect()->route('chirps.index');
    }

}
