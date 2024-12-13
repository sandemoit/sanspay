<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\WhatsApp;
use App\Http\Controllers\Controller;
use App\Models\Config;
use Exception;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function website()
    {
        $website = Config::whereIn('key', ['title', 'short_title', 'logo', 'favicon', 'web_description', 'web_keyword'])->get()->keyBy('key');

        // Inisialisasi array $data dengan nilai-nilai yang diambil
        $data = [
            'title' => $website->get('title'),
            'short_title' => $website->get('short_title'),
            'logo' => $website->get('logo'),
            'favicon' => $website->get('favicon'),
            'web_description' => $website->get('web_description'),
            'web_keyword' => $website->get('web_keyword'),
        ];

        return view('admin.config.website', $data);
    }

    public function updateWebsite(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'short_title' => 'required|string|max:50',
                'logo' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'favicon' => 'file|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
                'web_description' => 'required|string|max:255',
                'web_keyword' => 'required|string',
                'maintenance' => 'required|boolean',
            ]);

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('images', 'public');
                $validated['logo'] = $logoPath;
            }

            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('images', 'public');
                $validated['favicon'] = $faviconPath;
            }

            // Proses upload logo jika ada
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('public/images');
                $validated['logo'] = str_replace('public/', 'storage/', $logoPath); // Sesuaikan path
            }

            // Proses upload favicon jika ada
            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('public/images');
                $validated['favicon'] = str_replace('public/', 'storage/', $faviconPath); // Sesuaikan path
            }

            foreach ($validated as $key => $value) {
                $value = $value ?? 0; // Set nilai default jika kosong
                Config::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->back()->with('success', __('Data updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function contact()
    {
        $contact = Config::whereIn('key', ['whatsapp_url', 'instagram_url', 'telegram_url', 'facebook_url'])->get()->keyBy('key');

        // Inisialisasi array $data dengan nilai-nilai yang diambil
        $data = [
            'whatsapp_url' => $contact->get('whatsapp_url'),
            'instagram_url' => $contact->get('instagram_url'),
            'telegram_url' => $contact->get('telegram_url'),
            'facebook_url' => $contact->get('facebook_url'),
        ];

        return view('admin.config.contact', $data);
    }

    public function updateContact(Request $request)
    {
        try {
            $validated = $request->validate([
                'whatsapp_url' => 'required|string|max:255',
                'instagram_url' => 'required|string|max:255',
                'telegram_url' => 'required|string|max:255',
                'facebook_url' => 'required|string|max:255',
            ]);

            foreach ($validated as $key => $value) {
                $value = $value ?? 0; // Set nilai default jika kosong
                Config::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->back()->with('success', __('Data updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function mail()
    {
        $contact = Config::whereIn('key', ['smtp_username', 'smtp_password', 'smtp_server', 'smtp_from'])->get()->keyBy('key');

        // Inisialisasi array $data dengan nilai-nilai yang diambil
        $data = [
            'smtp_username' => $contact->get('smtp_username'),
            'smtp_password' => $contact->get('smtp_password'),
            'smtp_server' => $contact->get('smtp_server'),
            'smtp_from' => $contact->get('smtp_from'),
        ];

        return view('admin.config.mail', $data);
    }

    public function updateMail(Request $request)
    {
        try {
            $validated = $request->validate([
                'smtp_username' => 'required|string|max:255',
                'smtp_password' => 'required|string|max:255',
                'smtp_server' => 'required|string|max:255',
                'smtp_from' => 'required|string|max:255',
            ]);

            foreach ($validated as $key => $value) {
                $value = $value ?? 0; // Set nilai default jika kosong
                Config::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->back()->with('success', __('Data updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function wa()
    {
        $contact = Config::whereIn('key', ['wa_token', 'wa_url'])->get()->keyBy('key');

        // Inisialisasi array $data dengan nilai-nilai yang diambil
        $data = [
            'title' => 'WhatsApp Gateway',
            'wa_token' => $contact->get('wa_token'),
            'wa_url' => $contact->get('wa_url'),
        ];

        return view('admin.config.wa', $data);
    }

    public function updateWa(Request $request)
    {
        try {
            $validated = $request->validate([
                'wa_token' => 'required|string|max:255',
                'wa_url' => 'required|string|max:255',
            ]);

            foreach ($validated as $key => $value) {
                $value = $value ?? 0; // Set nilai default jika kosong
                Config::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            return redirect()->back()->with('success', __('Data updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __($e->getMessage()));
        }
    }

    public function testWa()
    {
        $target = request('wa_number');
        $message = 'Test pesan via WhatsApp Gateway';

        $result = WhatsApp::sendMessage($target, $message);

        if ($result['success']) {
            return redirect()->back()->with('success', __('Pesan berhasil dikirim'));
        } else {
            return redirect()->back()->with('error', __($result['message']));
        }
    }
}
