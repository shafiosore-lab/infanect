<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'exists:roles,id'],
        ];

        // Get role slug if role_id is set
        $roleSlug = null;
        if ($this->role_id) {
            $role = \App\Models\Role::find($this->role_id);
            if ($role) {
                $roleSlug = $role->slug;
            }
        }

        // Department is only required for employees or providers
        if (in_array($roleSlug, ['employee', 'provider', 'activity_provider'])) {
            $rules['department'] = ['required', 'string', 'max:255'];
        } else {
            $rules['department'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }
}
