<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\WhatsApp;
use App\Http\Controllers\Controller;
use App\Mail\WellcomeEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $user = $request->user();
        $user->update(['status' => 'active']);

        // Tambahkan flash message
        session()->flash('success', 'Akun Anda telah berhasil diverifikasi.');
        WhatsApp::sendMessage($request->user()->number, 'Selamat, akun Anda telah berhasil diverifikasi. www.sanspay.id');
        Mail::to($user->email)->send(new WellcomeEmail($user));

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
