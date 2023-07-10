<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'ticket_event_id' => 'required|exists:events,id',
            'ticket_name' => 'required',
            'ticket_price' => 'required|numeric',
            'ticket_quantity' => 'required|integer|min:1',
        ];
    }
}
