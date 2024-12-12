<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function show()
    {
        return view("auth.login");
    }

    public function login()
    {
        // Validate input
        validator(request()->all(), [
            "email" => ['required', 'email'],
            'password' => ['required']
        ])->validate();
    
        // Attempt login
        if (auth()->attempt(request()->only(['email', 'password']))) {
            if (auth()->user()->role !== 1) {
                auth()->logout();
                session()->flash('message', ['title' => 'Error', 'message' => 'You are not authorized to access this page.', 'type' => 'error']);
                return redirect()->route('login');
            }
    
            return redirect('/');
        }
    
        session()->flash('message', ['title' => 'Error', 'message' => 'Invalid credentials', 'type' => 'error']);
        return redirect()->back();
    }
    
    public function logout()
    {
        auth()->logout();
        session()->flash('message', ['title' => 'Notification', 'message' => 'You have been logged out successfully.', 'type' => 'success']);
        return redirect()->route('login');
    }
    
}
