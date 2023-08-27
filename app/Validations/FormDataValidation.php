<?php
namespace App\Validations;

use Illuminate\Foundation\Http\FormRequest;

class FormDataValidation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['bail', 'required'],
            'logo' => ['bail', 'required', 'file'],
        ];
    }
}
