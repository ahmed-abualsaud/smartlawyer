<?php

namespace App\Http\Requests\API;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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

    public function rules()
    {
        return [
            'name' => 'required|min:1|max:250|string',
            'email' => 'required|email|unique:users,email|max:250',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|string|max:25',
//            'national_id' => 'required|digits:14|unique:users,national_id|regex:/^2/',
            'national_id' => 'required|digits:10|unique:users,national_id',
            'role'  => ['required', 'string', Rule::in(['user', 'office'])],
            'file' => 'required'
        ];
    }

    // override failed validation
    public function failedValidation(Validator $validator) {
        $errors = [];
        $error = $validator->messages();
        foreach($error->all() as $value){
            $errors[] = $value;
        }
        throw new HttpResponseException(response()->json(['msg' => $errors], 400));
    }
}
