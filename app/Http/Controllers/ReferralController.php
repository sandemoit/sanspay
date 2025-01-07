<?php

namespace App\Http\Controllers;

use App\Models\Referrals;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReferralController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        $data = [
            'title' => 'Program Referral',
            'user' => $currentUser,
            'total_referral' => Referrals::where('username_from', $currentUser->name)->count(),
            'total_point' => Referrals::where('username_from', $currentUser->name)->sum('point'),
        ];

        return view('users.referral.index', $data);
    }

    public function getData()
    {
        $currentUser = Auth::user();
        $referrals = Referrals::select(['id', 'username_from', 'username_to', 'point', 'status', 'created_at'])->where('username_from', $currentUser->name)->get();

        return DataTables::of($referrals)
            ->addColumn('status', function ($row) {
                return '<span class="badge bg-' . ($row->status == 'active' ? 'success' : 'danger') . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('date', function ($row) {
                return tanggal($row->created_at);
            })
            ->addColumn('action', function ($row) {
                return '
                        <a class="btn btn-sm btn-info" href="' . route('referral.detail', $row->id) . '"><ion-icon name="eye-outline"></ion-icon></a>
                        ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function detail($id)
    {
        $referral = Referrals::findOrFail($id);
        return view('users.referral.detail', compact('referral'));
    }
}
