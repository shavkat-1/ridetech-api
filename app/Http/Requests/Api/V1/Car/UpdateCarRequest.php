<?php

namespace App\Http\Requests\Api\V1\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
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
        $carId = $this->route('car'); // Получаем ID машины из маршрута

        return [
            'brand' => ['sometimes', 'string', 'max:255'],
            'model' => ['sometimes', 'string', 'max:255'],
            'year' => ['sometimes', 'integer', 'min:1900', 'max:' . date('Y')],
            'license_plate' => [
                'sometimes',
                 'string',
                 Rule::unique('cars', 'license_plate')->ignore($carId) // Игнорируем текущую машину
            ],
        ];
    }
}
