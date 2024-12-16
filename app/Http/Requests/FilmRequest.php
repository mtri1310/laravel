<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Check if updating (if there's an ID in the route), if so, ignore unique check for film_name
        $filmId = $this->route('film') ? $this->route('film')->id : null;

        return [
            'film_name'    => 'required|string|max:255|unique:films,film_name,' . $filmId,
            'thumbnail'    => 'image|mimes:jpeg,png,jpg|max:2048',
            'duration'     => 'required|string|max:50',
            'review'       => 'nullable|numeric|min:0|max:10',
            'story_line'   => 'nullable|string',
            'movie_genre'  => 'required|string|max:255',
            'censorship'   => 'nullable|string|max:255',
            'language'     => 'required|string|max:255',
            'director'     => 'required|string|max:255',
            'actor'        => 'required|string|max:255',
            'release'      => 'required|date',
            'status'       => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'film_name.required'   => 'Film name is required.',
            'film_name.string'     => 'Film name must be a string.',
            'film_name.unique'     => 'The film name has already been taken.',
            'thumbnail.image'      => 'Thumbnail must be an image.',
            'thumbnail.mimes'      => 'Thumbnail must be a file of type: jpeg, png, jpg',
            'duration.required'    => 'Duration is required.',
            'duration.string'      => 'Duration must be a string.',
            'review.numeric'       => 'Review must be a number.',
            'review.min'           => 'Review must be at least 0.',
            'review.max'           => 'Review may not be greater than 5.',
            'release.date'         => 'Release date must be a valid date.',
            'status.boolean'       => 'Status must be true or false.',
        ];
    }
}
