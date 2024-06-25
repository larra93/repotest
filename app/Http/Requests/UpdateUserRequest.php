<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        
        $userId = $this->route('user'); 

        return [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'El correo electrónico ya está en uso.',
            'roles.*.exists' => 'Uno o más roles seleccionados no existen.',
        ];
    }
}
