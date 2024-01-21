<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportEventRequest extends FormRequest
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
    public function rules()
    {
      return [
            'export_type' => 'required',
            'event_start_date' => 'required',
            'event_end_date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'export_type.required' => 'The event export type field is required.',
            'event_start_date.required' => 'The event start date field is required.',
            'event_end_date.required' => 'The event end date field is required.',
        ];
    }
}
