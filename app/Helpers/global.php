<?php

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
            $profits = Profit::whereIn('key', ['admin', 'customer', 'agent'])->get()->keyBy('key');
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
            $web = Notification::whereIn('key', ['deposit_wa', 'deposit_email', 'transaction_wa', 'transaction_email'])->get()->keyBy('key');
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
