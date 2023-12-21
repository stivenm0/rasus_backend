<?php

namespace App\Http\Requests;

use app\traits\ResponseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'nickname'=> 'required|string|min:6|max:80',
            'email'=> 'required|string|email|'.
            Rule::unique('users')->ignore(Auth::user()->id, 'id')
            .'|min:6|max:100',
            'photo'=> 'sometimes|image|mimes:jpeg,png,jpg,gif|max:5000'

        ];
    }
}
