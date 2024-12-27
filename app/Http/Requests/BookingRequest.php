<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust authorization logic as needed
    }

    public function rules()
    {
        return [
            'showtime_id' => 'required|exists:showtimes,id',
            'user_id' => 'required|exists:users,id',
            'seat_id' => 'nullable|exists:seats,id',
        ];
    }

    public function messages()
    {
        return [
            'showtime_id.required' => 'The showtime field is required.',
            'showtime_id.exists' => 'The selected showtime is invalid.',
            'user_id.required' => 'The user field is required.',
            'user_id.exists' => 'The selected user is invalid.',
            'seat_id.required' => 'The seat field is required.',
            'seat_id.exists' => 'The selected seat is invalid.',
        ];
    }
}