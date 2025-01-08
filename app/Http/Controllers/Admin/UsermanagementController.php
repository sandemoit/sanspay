<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Upgrade;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

use function Laravel\Prompts\select;

class UsermanagementController extends Controller
{
    public function upgradeMitra()
    {
        $data = [
            'title' => 'Upgrade Mitra',
        ];

        return view('admin.user-management.upgrade', $data);
    }

    public function getUpgradeMitra()
    {
        // Jika user adalah admin, ambil semua data orders tanpa filter
        $orders = Upgrade::with(['user' => function ($q) {
            $q->select('id', 'fullname');
        }])->select('id', 'user_id', 'no_ktp', 'selfie_ktp', 'gender', 'full_address', 'status')->get();

        return DataTables::of($orders)
            ->addColumn('date', function ($row) {
                return tanggal($row->created_at);
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->fullname;
            })
            ->addColumn('status_case', function ($row) {
                if ($row->status == 'decline') {
                    return '<span class="badge bg-danger">Gagal</span>';
                } elseif ($row->status == 'pending') {
                    return '<span class="badge bg-warning">Pending</span>';
                } elseif ($row->status == 'accept') {
                    return '<span class="badge bg-success">Diterima</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-success btn-detail" data-id="' . $row->id . '" data-bs-toggle="modal"
                        data-bs-target="#detailModal">
                    <ion-icon name="eye-outline"></ion-icon> Detail
                </button>';
            })
            ->rawColumns(['user_name', 'date', 'status_case', 'action'])
            ->make(true);
    }

    public function getDetailUpgrade($id)
    {
        $pengajuan = Upgrade::find($id); // Model sesuai dengan tabel Anda
        if (!$pengajuan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json(
            [
                'no_ktp' => $pengajuan->no_ktp,
                'selfie_ktp' => asset('storage/' . $pengajuan->selfie_ktp),
                'status' => $pengajuan->status
            ]
        );
    }

    public function updateStatusUpgrade(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accept,decline',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }

        // Temukan data pengajuan dengan relasi user
        $pengajuan = Upgrade::with('user')->find($id);
        if (!$pengajuan) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Update data pengajuan
        $pengajuan->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        $pengajuan->user->update([
            'no_ktp' => $pengajuan->no_ktp,
            'selfie_ktp' => $pengajuan->selfie_ktp,
            'gender' => $pengajuan->gender,
            'full_address' => $pengajuan->full_address,
        ]);

        // Update role user jika status diterima
        if ($request->status === 'accept') {
            $pengajuan->user->update(['role' => $pengajuan->level]);
        }

        // Siapkan data untuk email
        $emailData = $this->prepareEmailData($pengajuan);

        // Kirim email dan tangani error
        try {
            Mail::to($pengajuan->user->email)->send(new SendEmail($emailData));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Status updated, but failed to send email', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Status updated successfully']);
    }

    private function prepareEmailData($pengajuan)
    {
        $statusMapping = [
            'accept' => [
                'subject' => 'Hore, Kamu berhasil naik level :D',
                'title' => 'Pengajuan Diterima',
                'status' => '2',
            ],
            'decline' => [
                'subject' => 'Yahh, Maaf pengajuan kamu ditolak :(',
                'title' => 'Pengajuan Ditolak',
                'status' => '3',
            ],
        ];

        $statusInfo = $statusMapping[$pengajuan->status];

        return [
            'subject' => $statusInfo['subject'] . ' | ' . $pengajuan->user->fullname,
            'title' => $statusInfo['title'],
            'body' => 'Lihat detail pengajuan di link berikut: ' . route('upgrade.result', ['status' => $statusInfo['status']]),
        ];
    }
}
