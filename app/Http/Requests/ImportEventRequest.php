<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportEventRequest extends FormRequest
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
        'import_type' => 'required',
        'import_file' => 'required|mimes:csv,txt,ics|max:4096',
        // 'import_file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,text/calendar|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'import_type.required' => 'The event import type field is required.',
            'import_file.required' => 'The event import file is required.',
            'import_file.mimes' => 'Only support CSV/CALENDAR format.',
            'import_file.size' => 'Maximum upload image size 4MB.',
        ];
    }
}
