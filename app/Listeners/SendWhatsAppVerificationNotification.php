<?php

namespace App\Listeners;

use App\Helpers\WhatsApp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class SendWhatsAppVerificationNotification implements ShouldQueue
{
    public function handle(Registered $event)
    {
        // Mengambil URL verifikasi yang sama dengan yang dikirim ke email
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $event->user->id,
                'hash' => sha1($event->user->email),
            ]
        );

        try {
            $message = "Sans Pay | Agen Resmi dan Murah di Indonesia\n\n";
            $message .= "Harap verifikasi email Anda dengan mengklik tautan di bawah ini:\n\n";
            $message .= $verificationUrl;
            $message .= "\n\nJika Anda tidak meminta pendaftaran, abaikan pesan ini.";
            $message .= "\n\nSalam,";
            $message .= "\nSans Pay | Agen Resmi dan Murah di Indonesia";

            // Menggunakan helper WhatsApp yang sudah ada
            $result = WhatsApp::sendMessage($event->user->number, $message);

            if (!$result['success']) {
                Log::error('Gagal mengirim verifikasi WhatsApp: ' . json_encode($result));
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim verifikasi WhatsApp: ' . $e->getMessage());
        }
    }
}
