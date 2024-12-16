<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Exception\ApiError;

class FilmController extends Controller
{
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    //
    public function index(): View
    { 
        $films = Film::all();
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

    
    public function store(StoreFilmRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $cloudinary = new Cloudinary();

                // Upload the image to Cloudinary
                $uploadResult = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'films/thumbnails', // Optional: specify a folder
                    'resource_type' => 'image',
                ]);

                // Save the secure URL to the database
                $data['thumbnail'] = $uploadResult['secure_url'];
            }

            Film::create($data);
            session()->flash('messageSuccess', 'New film is added successfully.');
            return redirect()->route('films.index');
        } catch (ApiError $e) {
            // Handle Cloudinary API errors
            return back()->withErrors(['thumbnail' => 'Image upload failed: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
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
    public function update(UpdateFilmRequest $request, Film $film): RedirectResponse
    {
        try {
            $data = $request->all();

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $cloudinary = new Cloudinary();

                // Upload the new image to Cloudinary
                $uploadResult = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'films/thumbnails', // Optional: specify a folder
                    'resource_type' => 'image',
                ]);

                // Save the secure URL to the database
                $data['thumbnail'] = $uploadResult['secure_url'];
            }

            $film->update($data);
            return redirect()->back()->withSuccess('Film is updated successfully.');
        } catch (ApiError $e) {
            // Handle Cloudinary API errors
            return back()->withErrors(['thumbnail' => 'Image upload failed: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Handle other exceptions
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
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
