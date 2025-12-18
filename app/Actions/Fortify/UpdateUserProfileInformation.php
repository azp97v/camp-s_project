<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
            'name.unique' => 'اسم المستخدم هذا مسجل بالفعل. يرجى اختيار اسم آخر.',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل. يرجى اختيار بريد آخر.',
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        try {
            if ($input['email'] !== $user->email &&
                $user instanceof MustVerifyEmail) {
                $this->updateVerifiedUser($user, $input);
            } else {
                $user->forceFill([
                    'name' => $input['name'],
                    'email' => $input['email'],
                ])->save();
            }
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

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        try {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();
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
