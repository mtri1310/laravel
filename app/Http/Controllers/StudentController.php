<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function index()
    {
        return response()->json([
            'name' => 'John Doe',
            'age' => 20,
        ]);
    }
}
