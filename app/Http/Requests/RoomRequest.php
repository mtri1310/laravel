<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
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
        $room = $this->route('room'); 
    
        // Quy tắc chung
        $rules = [
            'room_name' => 'required|string|max:255|unique:rooms,room_name,' . $roomId,
            'capacity'  => 'required|integer|min:1',
            'room_type' => 'nullable|string|max:50',
        ];

        // Nếu là phương thức PUT (chỉnh sửa), thêm quy tắc min cho capacity
        if ($this->isMethod('put') && $room) {
            $currentCapacity = $room->capacity;
            $rules['capacity'] = ['required', 'integer', 'min:' . ($currentCapacity + 1)];
        }

        return $rules;
    }
    
    public function messages()
    {
        $room = $this->route('room');
        $currentCapacity = $room ? $room->capacity : '1';

        return [
            'room_name.required' => 'Room name is required.',
            'room_name.string'   => 'Room name must be a string.',
            'room_name.unique'   => 'Room name has already been taken.',
            'capacity.required'  => 'Capacity is required.',
            'capacity.integer'   => 'Capacity must be an integer.',
            'capacity.min'       => $this->isMethod('put') 
                ? 'Capacity must be better than current capacity (' . $currentCapacity . ').'
                : 'Capacity must be at least 1.',
            'room_type.string'   => 'Room type must be a string.',
            'room_type.max'      => 'Room type must not exceed 50 characters.',
        ];
    }
    
}