<?php

namespace App\Http\Requests\API;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReplyComplaintRequest extends FormRequest
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
            'reply_text' => 'required|min:1|max:1000',
            'complaint_id' => 'required|numeric|exists:complaints,id',
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
