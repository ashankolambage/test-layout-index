<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'concessions' => 'required|array',
            'concessions.*.concession_id' => 'exists:concessions,id',
            'concessions.*.quantity' => 'required|integer|min:1',
            'send_to_kitchen_time' => 'required|date|after_or_equal:' . Carbon::now()->toDateTimeString(),
        ];
    }
}
