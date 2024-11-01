<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use Exception;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        $point = Point::whereIn('key', ['type', 'amount'])->get()->keyBy('key');

        // Inisialisasi array $data dengan nilai-nilai yang diambil
        $data = [
            'pageTitle' => 'Pengaturan Umum',
            'type' => $point->get('type'),
            'amount' => $point->get('amount'),
        ];

        return view('admin.pulsa-ppob.point', $data);
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|string',
                'amount' => 'required|integer',
            ]);

            foreach ($validated as $key => $value) {
                $value = $value ?? 0; // Set nilai default jika kosong
                Point::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->back()->with('success', __('Data updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }
}
