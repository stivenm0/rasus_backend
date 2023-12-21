<?php

namespace App\Http\Requests;

use App\Traits\ResponseRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    use ResponseRequest;
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
            'name'=> 'required|string|min:6|max:80',
            'email'=> 'required|string|email|unique:users,email|min:6|max:100',
            'password'=> 'required|confirmed|string|min:8|max:50'
        ];
    }
}
