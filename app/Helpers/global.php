<?php

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
