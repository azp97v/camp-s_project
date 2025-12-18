<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'name.unique' => 'اسم المستخدم هذا مسجل بالفعل. يرجى اختيار اسم آخر.',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل. يرجى اختيار بريد آخر.',
        ])->validate();

        try {
            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
        } catch (UniqueConstraintViolationException $e) {
            if (str_contains($e->getMessage(), 'name')) {
                throw ValidationException::withMessages([
                    'name' => 'اسم المستخدم هذا مسجل بالفعل. يرجى اختيار اسم آخر.',
                ]);
            }
            if (str_contains($e->getMessage(), 'email')) {
                throw ValidationException::withMessages([
                    'email' => 'البريد الإلكتروني مسجل بالفعل. يرجى اختيار بريد آخر.',
                ]);
            }
            throw $e;
        }
    }
}
