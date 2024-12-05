<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFilmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'film_name' => 'required|string|max:255|unique:films,film_name,' . $this->film->id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Kiểm tra tệp hình ảnh
            'duration' => 'required|string|max:50',
            'review' => 'nullable|numeric|min:0|max:10',
            'story_line' => 'nullable|string',
            'movie_genre' => 'required|string|max:100',
            'censorship' => 'nullable|string|max:50',
            'language' => 'required|string|max:50',
            'direction' => 'required|string|max:255',
            'actor' => 'required|string|max:500',
            'status' => 'required|boolean',
            'release' => 'required|boolean',
        ];
    }


}