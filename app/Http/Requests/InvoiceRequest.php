<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
        $roomId = $this->route('room') ? $this->route('room')->id : null;
    
        return [
            
        ];
    }
    
    public function messages()
    {
        return [
            'room_name.required' => 'Room name is required.',
            'room_name.string'   => 'Room name must be a string.',
            'room_name.unique'   => 'Room name has already been taken.',
            'capacity.required'  => 'Capacity is required.',
            'capacity.integer'   => 'Capacity must be an integer.',
            'capacity.min'       => 'Capacity must be at least 1.',
            'room_type.string'   => 'Room type must be a string.',
            'room_type.max'      => 'Room type must not exceed 50 characters.',
        ];
    }
    
}