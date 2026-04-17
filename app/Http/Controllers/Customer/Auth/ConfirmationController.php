<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Customer\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmationController extends Controller
{
    public function create(): View
    {
        return view('pages.customer.auth.confirm-password');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('customer')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('customer.dashboard', absolute: false));
    }
}

