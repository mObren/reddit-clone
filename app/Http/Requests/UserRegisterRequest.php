<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\IsUserRegisteredToReddit;

class UserRegisterRequest extends FormRequest
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
            'username' => ['required', 'unique:users,username','max:255', new IsUserRegisteredToReddit],
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|max:255|min:6',
        ];
    }
}
