<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referrals;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $title = 'Register';
        return view('auth.register', compact('title'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'number' => ['required', 'string', 'digits:12', 'unique:' . User::class],
            'pin' => ['required', 'string', 'digits:6'],
            'terms' => ['required', 'accepted'],
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $user = User::create([
            'fullname' => $request->fullname,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'pin' => Hash::make($request->pin),
            'number' => $request->number,
            'status' => 'inactive',
            'code_referral' => 'SP' . substr(str_shuffle('0123456789'), 0, 4),
        ]);

        if ($request->kode_referral) {
            $userReferral = User::where('code_referral', $request->kode_referral)->first();

            Referrals::create([
                'username_from' => $userReferral->name,
                'username_to' => $user->name,
                'point' => 2500,
                'status' => 'inactive'
            ]);

            $userReferral->increment('point', 2500);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
