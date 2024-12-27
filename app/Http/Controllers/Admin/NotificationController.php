<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notification = Notification::whereIn('key', ['create_deposit_wa', 'done_deposit_wa', 'transaction_wa', 'transaction_email'])->get()->keyBy('key');

        $data = [
            'title' => 'Notification',
            'create_deposit_wa' => $notification->get('create_deposit_wa'),
            'done_deposit_wa' => $notification->get('done_deposit_wa'),
            'transaction_wa' => $notification->get('transaction_wa'),
            'transaction_email' => $notification->get('transaction_email'),
        ];

        return view('admin.config.notification', $data);
    }

    public function updateNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'create_deposit_wa' => 'required|string',
                'done_deposit_wa' => 'required|string',
                'transaction_wa' => 'required|string',
                'transaction_email' => 'required|string',
            ]);

            foreach ($validated as $key => $value) {
                $value = $value ?? 0; // Set nilai default jika kosong
                Notification::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->back()->with('success', __('Data updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }
}
