<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeatRequest;
use App\Http\Requests\UpdateSeatRequest;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SeatController extends Controller
{
    //
    public function index() : View
    {
        return view('seats.index', [
            'seats' => Seat::latest()->paginate(3)
        ]);
    }

    public function create() : View
    {
        return view('seats.create');
    }

    public function store(StoreSeatRequest $request) : RedirectResponse
    {
        Seat::create($request->all());
        return redirect()->route('seats.index')
                ->withSuccess('New seat is added successfully.');
    }

    public function show(Seat $seat) : View
    {
        return view('seats.show', [
            'seat' => $seat
        ]);
    }

    public function edit(Seat $seat) : View
    {
        return view('seats.edit', [
            'seat' => $seat
        ]);
    }

    public function update(UpdateSeatRequest $request, Seat $seat) : RedirectResponse
    {
        $seat->update($request->all());
        return redirect()->back()
                ->withSuccess('Seat is updated successfully.');
    }

    public function destroy(Seat $seat) : RedirectResponse
    {
        $seat->delete();
        return redirect()->route('seats.index')
                ->withSuccess('Seat is deleted successfully.');
    }
}
