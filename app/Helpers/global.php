<?php

use App\Helpers\WhatsApp;
use App\Models\Announcement;
use App\Models\Config;
use App\Models\Notification;
use App\Models\Profit;
use App\Models\Provider;

if (!function_exists('currency')) {
    /**
     * Format angka menjadi format currency.
     *
     * @param  float|int  $angka
     * @param  string  $currency
     * @return string
     */
    function currency($angka, $currency = 'IDR')
    {
        if ($currency == 'IDR') {
            return 'Rp. ' . number_format($angka, 0, ',', '.');
        } elseif ($currency == 'USD') {
            return '$ ' . number_format($angka, 0, '.', ',');
        }
    }
}

if (!function_exists('nominal')) {
    /**
     * Format angka menjadi format nominal.
     *
     * @param  float|int  $angka
     * @param  string  $nominal
     * @return string
     */
    function nominal($angka, $nominal = 'IDR')
    {
        if ($nominal == 'IDR') {
            return number_format($angka, 0, ',', '.');
        } elseif ($nominal == 'USD') {
            $kurs = 15580;
            return '$ ' . number_format($angka / $kurs, 2, '.', ',');
        }
    }
}
if (!function_exists('tanggal')) {
    /**
     * Format tanggal menjadi string.
     *
     * @param  \DateTime  $tanggal
     * @param  string  $format
     * @return string
     */
    function tanggal(\DateTime $tanggal, $format = 'j F Y H:i')
    {
        return $tanggal->format($format);
    }
}

if (!function_exists('tanggalTrx')) {
    /**
     * Format tanggal menjadi string.
     *
     * @param  \DateTime  $tanggal
     * @param  string  $format
     * @return string
     */
    function tanggalTrx(\DateTime $tanggal, $format = 'j F Y H:i:s')
    {
        return $tanggal->format($format);
    }
}

if (!function_exists('provider')) {
    /**
     * Get model setting
     *
     * @param  string  $value
     * @return \App\Models\Provider
     */
    function provider($value = null)
    {
        static $providers;

        if (is_null($providers)) {
            $providers = Provider::whereIn('key', ['DigiFlazz', 'Midtrans'])->get()->keyBy('key');
        }

        if ($value) {
            return $providers->get($value);
        }

        return $providers;
    }
}

if (!function_exists('profit')) {
    /**
     * Get model setting
     *
     * @param  string  $value
     * @return \App\Models\Profit
     */
    function profit($value = null)
    {
        static $profits;

        if (is_null($profits)) {
            $profits = Profit::whereIn('key', ['admin', 'customer', 'mitra', 'type'])->get()->keyBy('key');
        }

        if ($value) {
            return $profits->get($value);
        }

        return $profits;
    }
}

if (!function_exists('configWeb')) {
    /**
     * Get model setting
     *
     * @param  string  $value
     * @return \App\Models\Profit
     */
    function configWeb($value = null)
    {
        static $web;

        if (is_null($web)) {
            $web = Config::whereIn('key', ['title', 'short_title', 'web_description', 'web_keyword', 'logo', 'favicon', 'maintenance', 'whatsapp_url', 'instagram_url', 'telegram_url', 'facebook_url', 'smtp_username', 'smtp_password', 'smtp_server', 'smtp_from', 'wa_token', 'wa_url'])->get()->keyBy('key');
        }

        if ($value) {
            return $web->get($value);
        }

        return $web;
    }

    function formatNotif($value = null)
    {
        static $web;

        if (is_null($web)) {
            $web = Notification::whereIn('key', ['create_deposit_wa', 'done_deposit_wa', 'deposit_email', 'transaction_wa', 'transaction_email'])->get()->keyBy('key');
        }

        if ($value) {
            return $web->get($value);
        }

        return $web;
    }
}

// untuk cek segment uri 1
if (!function_exists('segment')) {
    function segment($segment)
    {
        return request()->segment($segment);
    }
}

// buatkan function untuk announcement mengambil parameter dari field table announcement
function announcement()
{
    return Announcement::orderBy('updated_at', 'desc')->get();
}

if (!function_exists('normalizePhoneNumber')) {
    function normalizePhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '+62')) {
            return '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '62')) {
            return '0' . substr($phone, 2);
        }

        return $phone;
    }
}

if (!function_exists('detectProvider')) {
    function detectProvider($phone)
    {
        $prefixes = [
            'telkomsel' => ['0811', '0812', '0813', '0821', '0822', '0823', '0852', '0853'],
            'indosat'   => ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
            'xl'        => ['0817', '0818', '0819', '0859', '0877', '0878', '0879'],
            'axis'      => ['0831', '0832', '0833', '0834', '0835', '0836', '0837', '0838'],
            'tri'       => ['0895', '0896', '0897', '0898', '0899'],
            'smartfren' => ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
            'by.u'      => ['0851']
        ];

        foreach ($prefixes as $provider => $prefixList) {
            foreach ($prefixList as $prefix) {
                if (strpos($phone, $prefix) === 0) {
                    return $provider;
                }
            }
        }

        return null; // Provider tidak ditemukan
    }
}

if (!function_exists('isMode')) {
    function isMode()
    {
        return config('app.env') === 'local' ? 'local' : 'production';
    }
}

if (!function_exists('formatSN')) {
    /**
     * Format Serial Number (SN) berdasarkan jenisnya
     * 
     * @param string|null $sn
     * @param bool $raw Jika true, akan mengembalikan SN asli
     * @return string
     */
    function formatSN($sn, $raw = false)
    {
        if (!$sn) {
            return '-';
        }

        // Jika raw = true, kembalikan SN asli
        if ($raw) {
            return $sn;
        }

        // Jika dimulai dengan DNID, ambil bagian pertama sebelum slash
        if (str_starts_with($sn, 'DNID')) {
            $parts = explode('/', $sn);
            return $parts[0];
        }

        // Untuk token PLN, ambil sampai slash kedua
        $parts = explode('/', $sn);
        if (count($parts) >= 2 && strlen($parts[0]) >= 8) {
            return $parts[0] . '/' . $parts[1];
        }

        // Default: kembalikan SN asli jika tidak ada format yang sesuai
        return $sn;
    }
}

if (!function_exists('formatMessage')) {
    /**
     * Format pesan dengan mengganti placeholder dengan nilai yang sesuai
     * 
     * @param string $key Kunci notifikasi
     * @param array $values Array nilai pengganti [name, id, method, amount, dst]
     * @return array Response dari WhatsApp API
     */
    function formatMessage($key, $param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null, $param6 = null, $param7 = null, $param8 = null, $param9 = null, $param10 = null)
    {
        // Dapatkan template pesan dari database
        $message = formatNotif($key)->value;

        // Definisikan placeholder yang akan diganti
        $searchValues = [
            '{name}',
            '{var1}',
            '{var2}',
            '{var3}',
            '{var4}',
            '{var5}',
            '{var6}',
            '{var7}',
            '{var8}',
            '{var9}',
            '{var10}'
        ];

        // Siapkan nilai pengganti sesuai parameter yang diberikan
        $replaceValues = array_filter([
            $param1,
            $param2,
            $param3,
            $param4,
            $param5,
            $param6,
            $param7,
            $param8,
            $param9,
            $param10
        ], function ($value) {
            return !is_null($value);
        });

        // Hanya ambil placeholder sesuai jumlah parameter yang diberikan
        $searchValues = array_slice($searchValues, 0, count($replaceValues));

        // Ganti placeholder dengan nilai yang sesuai
        return str_replace($searchValues, $replaceValues, $message);
    }
}

if (!function_exists('sendWhatsAppMessage')) {
    /**
     * Kirim pesan WhatsApp dengan format yang sudah ditentukan
     * 
     * @param string $target Nomor tujuan
     * @param string $key Kunci notifikasi
     * @param array $params Parameter untuk format pesan
     * @return array Response dari WhatsApp API
     */
    function sendWhatsAppMessage($target, $key, ...$params)
    {
        // Format pesan menggunakan helper formatMessage
        $message = formatMessage($key, ...$params);

        // Kirim pesan menggunakan WhatsApp helper
        return WhatsApp::sendMessage($target, $message);
    }
}
