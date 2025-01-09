<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
// OR with multi

// OR
use Artesaos\SEOTools\Facades\SEOTools;

class WebController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Sans Pay | Agen Resmi dan Murah di Indonesia');
        SEOMeta::setDescription(configWeb('web_description')->value);
        SEOMeta::addKeyword([
            'agen pulsa',
            'ppob murah',
            'cepat',
            'aman',
            'pulsa elektrik',
            'transaksi cepat',
            'layanan terpercaya',
            'agen PPOB',
            'pembayaran tagihan',
            'pulsa murah',
            'transaksi aman',
            'pembayaran online',
            'agen pulsa terpercaya',
            'pulsa 24 jam',
            'pembayaran listrik',
            'pembayaran air',
            'agen pulsa dan PPOB',
            'layanan pulsa',
            'pembayaran internet',
            'pulsa semua operator',
            'transaksi mudah',
            'agen resmi',
            'layanan pelanggan',
            'diskon pulsa',
            'pembayaran cepat',
            'agen pulsa online',
            'pembayaran multifungsi',
            'layanan cepat',
            'agen PPOB terbaik',
            'pembayaran praktis',
            'pulsa dan PPOB',
            'transaksi tanpa ribet',
            'agen pulsa terpercaya di Indonesia',
            'layanan pembayaran tagihan',
            'pembayaran aman dan cepat'
        ]);
        SEOTools::opengraph()->setUrl('https://sanspay.id');
        SEOTools::setCanonical('https://sanspay.id');
        SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::twitter()->setSite('@sanspay.id');
        SEOTools::jsonLd()->addImage(asset('storage/images/f20vwsOXAIzYgf5UyrHcMJ2phHXY5NwLcmBlYhUz.svg'));

        TwitterCard::setTitle('Homepage');
        TwitterCard::setSite('@sanspay.id');

        OpenGraph::setDescription(configWeb('web_description')->value);
        OpenGraph::setTitle('Sans Pay | Agen Resmi dan Murah di Indonesia');
        OpenGraph::setUrl('https://sanspay.id');
        OpenGraph::addProperty('type', 'article');
        OpenGraph::addProperty('locale', 'id-ID');
        OpenGraph::addProperty('locale:alternate', ['id-ID', 'en-us']);

        OpenGraph::addImage(asset('storage/images/announcement/Cae8z7bNjeouCaHs5ps4bYMCrmzPaTvOD8Z09LE3.png'));
        OpenGraph::addImage(['url' => asset('storage/images/announcement/Cae8z7bNjeouCaHs5ps4bYMCrmzPaTvOD8Z09LE3.png'), 'size' => 300]);
        OpenGraph::addImage(asset('storage/images/announcement/Cae8z7bNjeouCaHs5ps4bYMCrmzPaTvOD8Z09LE3.png'), ['height' => 300, 'width' => 300]);

        return view('welcome');
    }
}
