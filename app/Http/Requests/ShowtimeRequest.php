<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; 

class ShowtimeRequest extends FormRequest
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
        // Nếu là update, lấy ID của showtime hiện tại
        $showtimeId = $this->route('showtime') ? $this->route('showtime')->id : null;

        return [
            'film_id' => 'required|integer|exists:films,id',  
            'room_id' => 'required|integer|exists:rooms,id', 
            'start_time' => 'required|date_format:H:i', // Đảm bảo start_time có định dạng giờ hợp lệ
            'day' => 'required|date|after_or_equal:today',  // Đảm bảo 'day' có định dạng ngày hợp lệ và không sớm hơn ngày hiện tại

        ];
    }


    public function messages()
    {
        return [
            'film_id.required'  => 'Film is required.',
            'film_id.integer'   => 'Film must be a valid integer.',
            'film_id.exists'    => 'Selected film does not exist.',
            'room_id.required'  => 'Room is required.',
            'room_id.integer'   => 'Room must be a valid integer.',
            'room_id.exists'    => 'Selected room does not exist.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Start time must be in the format HH:MM.',
            'day.required' => 'Day is required.',
            'day.date_format' => 'Day must be a valid date in the format YYYY-MM-DD.',
            'day.after_or_equal' => 'Day must be today or in the future.',
        ];
    }

}