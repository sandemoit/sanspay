<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profit;
use Exception;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index()
    {
        $profit = Profit::whereIn('key', ['admin', 'mitra', 'customer', 'type'])->get()->keyBy('key');

        // Inisialisasi array $data dengan nilai-nilai yang diambil
        $data = [
            'pageTitle' => 'Pengaturan Umum',
            'admin' => $profit->get('admin'),
            'mitra' => $profit->get('mitra'),
            'customer' => $profit->get('customer'),
            'type' => $profit->get('type'),
        ];

        return view('admin.pulsa-ppob.profit', $data);
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:1,2',
                'admin' => 'required|numeric',
                'mitra' => 'required|numeric',
                'customer' => 'required|numeric',
            ]);

            foreach ($validated as $key => $value) {
                $value = $value ?? 0; // Set nilai default jika kosong
                Profit::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->back()->with('success', __('Data updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }
}
