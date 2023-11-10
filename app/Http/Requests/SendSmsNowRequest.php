<?php

namespace App\Http\Requests;

use App\Rules\ValidNumber;
use Illuminate\Foundation\Http\FormRequest;

class SendSmsNowRequest extends FormRequest
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
            'recipient' => ['required', 'numeric', new ValidNumber],
            'body' => 'required|string|min:1|max:160'
        ];
    }
}
