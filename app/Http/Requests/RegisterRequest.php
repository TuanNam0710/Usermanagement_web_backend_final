<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'first_name.required' => 'Please enter first name!',
            'last_name.required' => 'Please enter last name!',
            'email.required' => 'Please enter email!',
            'email.unique' => 'Email has already been used by another user!',
            'username.required' => 'Please enter username!',
            'username.unique' => 'Username has already been used by another user!',
            'password.required' => 'Please enter password!',
            'password.confirmed' => 'Password do not match!',
            'password_confirmation.required' => 'Please re-type password!',
        ];
    }
}
