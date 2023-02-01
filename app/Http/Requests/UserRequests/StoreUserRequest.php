<?php

namespace App\Http\Requests\UserRequests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'role_id' => 'integer|between:1,2',
            'name' => 'required|string',
            'email' => 'email',
            'phone' => 'required|unique:users',
            'password' => 'required|string|min:6'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
