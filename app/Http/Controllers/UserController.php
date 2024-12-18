<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

use Illuminate\View\View;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
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
    //
    public function index(): View
    {
        return view('users.index', [
            'users' => User::latest()->paginate(3)
        ]);
    }

    public function create(): View
    {
        //
        return view('users.create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('picture')) {
                $data['picture'] = $this->cloudinaryService->uploadImage($request->file('picture'));
            }

            User::create($data);

            return redirect()->route('users.index')
                ->with('messageSuccess', 'New picture has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Picture Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the picture.');
        }
    }

    

    public function edit(User $user) : View
    {
        return view('users.create', compact('user'));
    }

    public function update(UserRequest $request, User $user) : RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('picture')) {
                $data['picture'] = $this->cloudinaryService->uploadImage($request->file('picture'));
            }

            User::create($data);

            return redirect()->route('user.index')
                ->with('messageSuccess', 'New picture has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('Picture Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while updating the picture.');
        }
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(User $user) : RedirectResponse
    {
        try {
            $imageUrl = $user->picture;

            if ($imageUrl) {
                $deleted = $this->cloudinaryService->deleteImageByUrl($imageUrl);
                if (!$deleted) {
                    Log::error("Failed to delete image from Cloudinary for User ID: {$user->id}");
                }
            }
            $user->delete();

            return redirect()->route('users.index')
                ->with('messageSuccess', 'User has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error('User Deletion Failed: ' . $e->getMessage());
            return back()->with('messageError', 'Failed to delete the user.');
        }
        
    }
}
