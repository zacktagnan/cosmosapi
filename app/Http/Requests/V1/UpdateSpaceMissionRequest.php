<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpaceMissionRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'destination' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'launch_date' => 'sometimes|date|after_or_equal:today',
            'duration_days' => 'sometimes|integer|min:1|max:10000',
            'status' => 'sometimes|in:planned,active,completed,failed,cancelled',
            'agency' => 'sometimes|string|max:255',
            'crew_size' => 'sometimes|integer|min:1|max:50',
            'mission_type' => 'sometimes|in:exploration,research,colonization,mining,rescue',
            'budget_millions' => 'sometimes|numeric|min:1|max:999999.99',
        ];
    }
}
