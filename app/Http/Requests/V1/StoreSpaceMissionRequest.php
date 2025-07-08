<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpaceMissionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'launch_date' => 'required|date|after_or_equal:today',
            'duration_days' => 'required|integer|min:1|max:10000',
            'status' => 'required|in:planned,active,completed,failed,cancelled',
            'agency' => 'required|string|max:255',
            'crew_size' => 'required|integer|min:1|max:50',
            'mission_type' => 'required|in:exploration,research,colonization,mining,rescue',
            'budget_millions' => 'required|numeric|min:1|max:999999.99',
        ];
    }
}
