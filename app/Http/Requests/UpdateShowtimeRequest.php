<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShowtimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'film_id' => 'required|string|max:50|exists:films,id'.$this->showtime->id,  
            'room_id' => 'required|string|max:50|exists:rooms,id'.$this->showtime->id,  
            'day' => 'required|date|after_or_equal:today',  
            'start_time' => 'required|date_format:H:i',
        ];
    }
}