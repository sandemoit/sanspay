<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public $title; // Tambahkan properti untuk title  

    public function __construct($title = 'Agen Resmi dan Murah di Indonesia') // Berikan nilai default  
    {
        $this->title = $title; // Simpan title ke properti  
    }

    public function render(): View
    {
        return view('layouts.guest', ['title' => $this->title]); // Kirim title ke view  
    }
}
