<?php

namespace App\Http\Requests\Invitation;

use App\Enums\CompanyRole;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'company_id' => ["required", "exists:companies,id"],
            'role' => ["required"], //TODO Add all existing roles from Enum or figure out how to cast them here
        ];
    }
}
