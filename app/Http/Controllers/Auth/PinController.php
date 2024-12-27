<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PinController extends Controller
{
    public function pinUpdate(Request $request)
    {
        $request->validate([
            'password' => ['required'], // Validasi password
            'pin' => ['required', 'string', 'min:6', 'max:6', 'confirmed'], // Validasi PIN baru dan konfirmasi
        ]);

        // Verifikasi password
        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'Password yang Anda masukkan salah.']);
        }

        // Update PIN jika password valid
        $request->user()->update([
            'pin' => bcrypt($request->pin), // Hash PIN sebelum disimpan
        ]);

        return back()->with('success', 'PIN successfully updated.');
    }
}
