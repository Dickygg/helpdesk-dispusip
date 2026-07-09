<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    protected string $loginField = 'username';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            $this->loginField => ['required', 'string'],
            'password'        => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        $input = $this->input('username');
        // cekuser name lama
        // $user = \App\Models\User::where($this->loginField, $this->input($this->loginField))->first();

        // Cek username 
        $user = \App\Models\User::where('username', $input)
            ->orWhere('nrk', $input)
            ->first();
        if (! $user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $this->loginField => 'Username atau NRK tidak ditemukan.',
            ]);
        }

        // Cek password lama
        // if (! Auth::attempt([
        //     $this->loginField => $this->input($this->loginField),
        //     'password'        => $this->input('password'),
        // ], $this->boolean('remember'))) {
        //     RateLimiter::hit($this->throttleKey());

        //     throw ValidationException::withMessages([
        //         'password' => 'Password yang Anda masukkan salah.',
        //     ]);
        // }

        if (! \Illuminate\Support\Facades\Hash::check($this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Password yang Anda masukkan salah.',
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            $this->loginField => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string($this->loginField)) . '|' . $this->ip()
        );
    }
}
