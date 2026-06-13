<?php

namespace App\Http\Requests\Api\V1\Trip;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'origin' => ['sometimes', 'string', 'max:255'],
            'destination' => ['sometimes', 'string', 'max:255'],
            'departure_time' => ['sometimes', 'date_format:Y-m-d H:i:s', 'after:now'],
            'preferences' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }
}
