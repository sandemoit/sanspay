<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Upgrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        return view('users.dashboard', compact('title'));
    }

    public function upgradeMitra(Request $request)
    {
        // Ambil user yang sedang login
        $userId = Auth::id();

        // Cari status upgrade berdasarkan user ID
        $upgrade = Upgrade::where('user_id', $userId)->first();

        if ($upgrade) {
            if ($upgrade->status == 'pending') {
                return redirect()->route('upgrade.result', ['status' => '1']);
            }
        }

        $data = [
            'title' => 'Upgrade Mitra',
        ];

        return view('users.upgrade.index', $data);
    }

    public function saveUpgradeMitra(Request $request)
    {
        // Validasi input
        $request->validate([
            'full_address' => 'required|max:255',
            'gender' => 'required|in:Laki - laki,Perempuan',
            'no_ktp' => 'required|numeric|digits:16|unique:antrian_upgrade,no_ktp',
            'selfie_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = Auth::id();
        $user = Auth::user();

        try {
            // Upload file selfie_ktp
            $path = $request->file('selfie_ktp')->store('selfie_ktp', 'public');

            // Simpan atau update data di database
            Upgrade::updateOrCreate(
                // Kondisi pencarian
                ['user_id' => $userId],
                // Data yang akan diupdate atau disimpan
                [
                    'level' => 'mitra',
                    'full_address' => strip_tags($request->full_address),
                    'gender' => strip_tags($request->gender),
                    'no_ktp' => strip_tags($request->no_ktp),
                    'selfie_ktp' => strip_tags($path),
                    'status' => 'pending', // Default status
                ]
            );

            $data = [
                'subject' => 'Pengajuan Upgrade Mitra | ' . $user->fullname,
                'title' => 'Pengajuan Upgrade Mitra',
                'body' => 'Pengajuan upgrade mitra telah berhasil diajukan oleh, mohon tunggu konfirmasi dari admin kami 1x24 JAM.',
            ];

            Mail::to($user->email)->send(new SendEmail($data));

            // Redirect dengan pesan sukses
            return redirect()->route('upgrade.result', ['status' => 1])->with('success', 'Permintaan upgrade mitra berhasil diajukan.');
        } catch (\Exception $e) {
            // Redirect dengan pesan error
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function upgradeStatus(Request $request)
    {
        // Ambil parameter status
        $paramsStatus = $request->get('status');

        // Validasi parameter: hanya angka 1 atau 2 yang diterima
        if (!in_array($paramsStatus, ['1', '2', '3'])) {
            abort(404, 'Parameter tidak valid!');
        }

        $userId = Auth::id();

        // Ambil data upgrade berdasarkan user yang sedang login
        $upgrade = Upgrade::where('user_id', $userId)->first();

        if (!$upgrade) {
            // Jika data upgrade tidak ditemukan, tampilkan pesan kesalahan
            abort(404, 'Data upgrade tidak ditemukan!');
        }

        // Validasi status dari database
        if ($paramsStatus == '1' && $upgrade->status !== 'pending') {
            abort(403, 'Status tidak valid untuk ditampilkan!');
        }

        if ($paramsStatus == '2' && $upgrade->status !== 'accept') {
            abort(403, 'Status tidak valid untuk ditampilkan!');
        }

        if ($paramsStatus == '3' && $upgrade->status !== 'decline') {
            abort(403, 'Status tidak valid untuk ditampilkan!');
        }

        $data = [
            'title' => 'Upgrade Status Mitra',
            'status' => $paramsStatus, // Kirim angka langsung ke view
        ];

        return view('users.upgrade.status', $data);
    }
}
