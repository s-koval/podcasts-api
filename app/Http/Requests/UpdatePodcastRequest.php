<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePodcastRequest extends FormRequest
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
            'name' => 'required|min:4',
            'description' => 'required|max:1000',
            'marketing_url' => 'url',
            'feed_url' => 'required|url',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name field must be greater than 4 characters.',
            'description.required' => 'The description field is required.',
            'description.max' => 'The description field must be less than 1000 characters.',
            'marketing_url.url' => 'The marketing url field must be a valid url address.',
            'feed_url.required' => 'The feed url field is required',
            'feed_url.url' => 'The feed url field must be a valid url address.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not update podcast.', $validator->errors());
    }
}
