<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

use Illuminate\View\View;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password); // Hash mật khẩu
            }

            if ($request->hasFile('picture')) {
                $data['picture'] = $this->cloudinaryService->uploadImage($request->file('picture'));
            }

            User::create($data);

            return redirect()->route('users.index')
                ->with('messageSuccess', 'New user has been added successfully.');
        } catch (\Exception $e) {
            Log::error('User Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the user.');
        }
    }

    

    public function edit(User $user) : View
    {
        return view('users.create', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password); // Hash mật khẩu
            } else {
                unset($data['password']); // Giữ nguyên mật khẩu nếu không thay đổi
            }

            if ($request->hasFile('picture')) {
                $data['picture'] = $this->cloudinaryService->uploadImage($request->file('picture'));
            }

            $user->update($data);

            return redirect()->route('users.index')
                ->with('messageSuccess', 'User has been updated successfully.');
        } catch (\Exception $e) {
            Log::error('User Update Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while updating the user.');
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
