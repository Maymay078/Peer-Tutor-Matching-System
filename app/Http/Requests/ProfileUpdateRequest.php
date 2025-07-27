<?php

namespace App\Http\Requests;

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
        $rules = [
            'full_name' => [
                'required',
                'string',
                'max:255',
                // Disallow common prefixes like Mr, Miss, Prof, Dr, etc.
                'not_regex:/^(Mr|Mrs|Miss|Ms|Dr|Prof)\b/i',
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'date_of_birth' => [
                'required',
                'date',
                'after_or_equal:1990-01-01',
                'before_or_equal:2006-12-31',
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^(?:\+?6?01)[0-46-9]-*[0-9]{7,8}$/',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'profile_image' => ['nullable', 'image', 'max:2048'], // max 2MB
        ];

        // Add role-specific validation rules
        if ($user && $user->role === 'student') {
            $rules['major'] = ['required', 'string', 'max:255'];
            $rules['year'] = ['required', 'integer', 'min:1', 'max:10'];
            $rules['preferred_course'] = ['nullable', 'string', 'max:255'];
        } elseif ($user && $user->role === 'tutor') {
            // Tutor fields
            $rules['expertise'] = ['nullable', 'array'];
            $rules['expertise.*.name'] = ['required_with:expertise', 'string', 'max:255'];
            $rules['expertise.*.price_per_hour'] = ['required_with:expertise', 'numeric', 'min:0'];
            $rules['payment_details'] = ['nullable', 'array'];
            $rules['payment_details.*'] = ['string', 'in:cash,online_banking'];

            // Availability validation for tutors
            $rules['availability'] = ['nullable', 'array'];
            $rules['availability.date*'] = ['nullable', 'date'];
            $rules['availability.time*'] = ['nullable', 'array'];
        }

        return $rules;
    }
}
