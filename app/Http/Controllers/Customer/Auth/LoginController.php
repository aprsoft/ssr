<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Customer\Controller;
use App\Models\Customer;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('pages.customer.auth.signin');
    }

    public function store(Request $request): RedirectResponse
    {
        // $request->validate([
        //     'rut' => ['required', 'string', 'regex:/^[0-9]{7,8}-[0-9kK]{1}$/'],
        //     'password' => ['required', 'string'],
        // ], [
        //     'rut.regex' => 'El RUT debe tener formato 12345678-9',
        // ]);

        $this->ensureIsNotRateLimited($request);

        // Limpiar RUT
        $rutLimpio = $this->limpiarRut($request->rut);

        // Buscar customer SOLO por RUT (la DB ya es del tenant correcto)
        $customer = Customer::query()
            // ->where('rut', $rutLimpio)
            ->where('rut', $request->rut)

            // ->where('is_active', true)
            ->first();

        // Verificar credenciales
        if (! $customer || ! password_verify($request->password, $customer->password)) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'rut' => 'Las credenciales no son válidas.',
            ]);
        }

        Auth::guard('customer')->login($customer, $request->boolean('remember'));
        
        RateLimiter::clear($this->throttleKey($request));
        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }

    protected function limpiarRut(string $rut): string
    {
        return str_replace(['.', '-'], '', strtolower($rut));
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'rut' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(Request $request): string
    {
        return Str::transliterate(
            Str::lower($request->string('rut')).'|'.$request->ip()
        );
    }
}