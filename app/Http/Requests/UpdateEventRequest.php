<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
        $reason = $this->request->get('cover_image'); // Get the input value
        return [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'location' => 'required|string|max:255',
            'event_category' => 'required|string|max:255',
            'event_start_date' => 'required',
            'event_start_time' => 'required',
            'event_end_time' => 'required',
        ];

        // Check condition to apply proper rules
        if (!empty($reason)) {
            $rules['cover_image'] = 'required|mimes:png,jpg,jpeg|max:4096';
        }
    }
}
