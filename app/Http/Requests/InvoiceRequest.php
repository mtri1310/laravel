<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $invoiceId = $this->route('invoice') ? $this->route('invoice')->id : null;

        return [
            'username' => ['required', Rule::exists('users', 'id')],
            'film' => ['required', Rule::exists('films', 'id')],
            'start_time' => ['required'],
            'day' => ['required', 'date', 'after_or_equal:today'],
            'room' => ['required', Rule::exists('rooms', 'id')],
            'seat_count' => ['required', 'integer', 'min:1', 'max:3'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'transaction_id' => [
                'required',
                Rule::unique('payments', 'transaction_id')->ignore($this->input('payment_id'), 'id'),
            ],
            'payment_method' => ['required', 'string', Rule::in(['Credit Card', 'PayPal', 'Cash'])],
            'payment_status' => ['required', 'string', Rule::in(['Completed', 'Pending', 'Failed'])],
            'payment_id' => ['required', Rule::exists('payments', 'id')],
        ];
    }
    
    public function messages(): array
    {
        return [
            'payment_id.required' => 'Payment ID is required.',
            'payment_id.exists' => 'Payment ID must exist in the payment table.',
            'invoice_number.unique' => 'Invoice Number must be unique.',
            'total_amount.required' => 'Total Amount is required.',
            'total_amount.numeric' => 'Total Amount must be a numeric value.',
        ];
    }

    
}