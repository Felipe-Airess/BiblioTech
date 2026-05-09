<?php

namespace App\Http\Requests;

use App\Models\Membros;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        // Use the correct model for uniqueness checks depending on the guard/user type.
        $userClass = $user instanceof Membros ? Membros::class : User::class;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,dns',
                'max:255',
                Rule::unique($userClass)->ignore($user->id),
            ],
        ];
    }
}
