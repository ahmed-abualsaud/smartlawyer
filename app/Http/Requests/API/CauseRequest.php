<?php

namespace App\Http\Requests\API;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CauseRequest extends FormRequest
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
            'title' => 'required|min:1|max:250|string',
            'number' => 'required|min:1|max:250|string',
            'judgment_date' => 'required|date_format:Y-m-d',
            'judgment_text' => 'required|min:1|max:250|string',
            'court_name' => 'required|min:1|max:250|string',
            'judicial_chamber' => 'required|min:1|max:250|string',
            'consideration_text' => 'nullable|min:1|max:250|string',
            'type' => 'required|min:1|max:250|string',
            'is_public'  => ['required', 'string', Rule::in([0, 1])],
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
