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
    public function create(): View
    {
        //
        return view('films.create');
    }

    public function store(StoreFilmRequest $request): RedirectResponse
    {
        try {
            // Thêm film vào cơ sở dữ liệu
            Film::create($request->all());

            // Thêm thông báo thành công vào session
            return redirect()->route('films.index')
                            ->with('messageSuccess', 'New film is added successfully.');
        } catch (\Exception $e) {
            // Nếu có lỗi, thêm thông báo lỗi vào session
            return redirect()->route('films.index')
                            ->with('messageError', 'An error occurred while adding the film.');
        }
    }

    // /**
    //  * Display the specified resource.
    //  */
    public function show(Film $film) : View
    {
        return view('films.show', [
            'film' => $film
        ]);
    }

    public function edit(Film $film) : View
    {
        return view('films.edit', [
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
