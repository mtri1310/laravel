<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\FilmRequest;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;

class FilmController extends Controller
{
    protected $cloudinaryService;

    /**
     * Constructor to inject CloudinaryService.
     *
     * @param CloudinaryService $cloudinaryService
     */
    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Display a listing of the films.
     *
     * @return View
     */
    public function index(Request $request): View
    { 
        $keyword = $request->input('keyword');

        $films = Film::latest()
                    ->when($keyword, function($query, $keyword) {
                        return $query->where(function($q) use ($keyword) {
                            $q->where('film_name', 'like', "%{$keyword}%")
                              ->orWhere('duration', 'like', "%{$keyword}%")
                              ->orWhere('review', 'like', "%{$keyword}%")
                              ->orWhere('movie_genre', 'like', "%{$keyword}%")
                              ->orWhere('censorship', 'like', "%{$keyword}%")
                              ->orWhere('language', 'like', "%{$keyword}%")
                              ->orWhere('director', 'like', "%{$keyword}%")
                              ->orWhere('actor', 'like', "%{$keyword}%");
                        });
                    })
                    ->paginate(10)
                    ->appends(['keyword' => $keyword]); // Đảm bảo từ khóa được giữ lại trong các liên kết phân trang

        return view('films.index', compact('films', 'keyword'));
    }

    /**
     * Show the form for creating a new film.
     *
     * @return View
     */
    public function create(): View
    {
        return view('films.create');
    }

    /**
     * Store a newly created film in storage.
     *
     * @param FilmRequest $request
     * @return RedirectResponse
     */
    public function store(FilmRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $this->cloudinaryService->uploadImage($request->file('thumbnail'));
            }

            Film::create($data);

            return redirect()->route('films.index')
                ->with('messageSuccess', 'New film has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Film Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the film.');
        }
    }

    /**
     * Show the form for editing the specified film.
     *
     * @param Film $film
     * @return View
     */
    public function edit(Film $film): View
    {
        return view('films.create', compact('film')); // Reusing the create view for editing
    }

    /**
     * Update the specified film in storage.
     *
     * @param FilmRequest $request
     * @param Film $film
     * @return RedirectResponse
     */
    public function update(FilmRequest $request, Film $film): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('thumbnail')) {
                // Xóa hình ảnh cũ trên Cloudinary
                if ($film->thumbnail) {
                    $this->cloudinaryService->deleteImageByUrl($film->thumbnail);
                }

                // Upload hình ảnh mới
                $data['thumbnail'] = $this->cloudinaryService->uploadImage($request->file('thumbnail'));
            } elseif ($request->input('existing_thumbnail')) {
                $data['thumbnail'] = $request->input('existing_thumbnail');
            }

            $film->update($data);

            return redirect()->route('films.index')
                ->with('messageSuccess', 'Film has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('Film Update Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while updating the film.');
        }
    }

    /**
     * Remove the specified film from storage.
     *
     * @param Film $film
     * @return RedirectResponse
     */
    public function destroy(Film $film): RedirectResponse
    {
        try {
            $imageUrl = $film->thumbnail;

            if ($imageUrl) {
                $deleted = $this->cloudinaryService->deleteImageByUrl($imageUrl);
                if (!$deleted) {
                    Log::error("Failed to delete image from Cloudinary for Film ID: {$film->id}");
                }
            }
            $film->delete();

            return redirect()->route('films.index')
                ->with('messageSuccess', 'Film has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Film Deletion Failed: ' . $e->getMessage());
            return back()->with('messageError', 'Failed to delete the film.');
        }
    }

}
