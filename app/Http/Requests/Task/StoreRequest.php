<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:tasks,name'],
            'description' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string'],
            'files.*' => ['nullable', 'file', 'mimes:pdf,docx,pptx,xlsx,png,jpeg', 'max:10240'],
            'user_id' => ['integer', 'exists:users,id']
        ];
    }
}
