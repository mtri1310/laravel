<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilmSearchController extends Controller
{
    /**
     * Search films by name ignoring accents.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByName(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $name = $request->input('name');

        // Convert input to lowercase and use collation for accent-insensitive search
        $results = DB::table('films')
            ->whereRaw("LOWER(film_name) LIKE LOWER(CONVERT(:name USING utf8mb4)) COLLATE utf8mb4_general_ci", [
                'name' => "%$name%"
            ])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }
}
