<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'author_name' => 'required',
            'author_email' => 'required',
            'comment' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'author_name.required' => 'The name field is required.',
            'author_email.required' => 'The email field is required.',
            'comment.required' => 'The comment field is required.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new comment.', $validator->errors());
    }

}
