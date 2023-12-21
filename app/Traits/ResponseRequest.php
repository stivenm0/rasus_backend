<?php

namespace App\Traits;

use App\Http\Responses\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ResponseRequest{
    
    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse::error(statusCode: 422, errors: $validator->errors());

        throw new HttpResponseException($response);
    }
}