<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use Carbon\Carbon;
class FilmController extends Controller
{
    //
    public function index(): View
    { 
        $films = Film::all();

        // Định dạng ngày 'release' theo định dạng 'dd/MM/yyyy' trước khi gửi vào view
        foreach ($films as $film) {
            $film->release = Carbon::parse($film->release)->format('d/m/Y');
        }
    
        return view('films.index', compact('films'));

    }

    
    // /**
    //  * Show the form for creating a new resource.
    //  */
    public function create(): View
    {
        //
        return view('films.create');
    }

    
    public function store(StoreFilmRequest $request) : RedirectResponse
    {
        try{
            Film::create($request->all());
            session()->flash('messageSuccess', 'New film is added successfully.');
            return redirect()->route('films.index');
        }
        catch(Exception $e){
            dd($e->getMessage());
        }
    }

    public function edit(Film $film) : View
    {
        return view('films.create', [
            'film' => $film
        ]);
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(UpdateFilmRequest $request, Film $film) : RedirectResponse
    {
        $film->update($request->all());
        return redirect()->back()
                ->withSuccess('Film is updated successfully.');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(Film $film) : RedirectResponse
    {
        $film->delete();
        return redirect()->route('films.index')
                ->withSuccess('Film is deleted successfully.');
    }
}
