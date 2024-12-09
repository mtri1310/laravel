<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;

class FilmController extends Controller
{
    //
    public function index(): View
    {
        return view('films.index', [
            'films' => Film::latest()->paginate(3)
        ]);
    }

    
    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function add()
    // {
    //     //
    //     return view('films.add');
    // }

    // public function store(StoreFilmRequest $request) : RedirectResponse
    // {
    //     Film::create($request->all());
    //     return redirect()->route('films.index')
    //             ->withSuccess('New film is added successfully.');
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Film $film) : View
    // {
    //     return view('films.show', [
    //         'film' => $film
    //     ]);
    // }

    // public function edit(Film $film) : View
    // {
    //     return view('films.edit', [
    //         'film' => $film
    //     ]);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(UpdateFilmRequest $request, Film $film) : RedirectResponse
    // {
    //     $film->update($request->all());
    //     return redirect()->back()
    //             ->withSuccess('Film is updated successfully.');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Film $film) : RedirectResponse
    // {
    //     $film->delete();
    //     return redirect()->route('films.index')
    //             ->withSuccess('Film is deleted successfully.');
    // }
}
