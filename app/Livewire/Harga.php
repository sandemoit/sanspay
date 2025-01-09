<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\ProductPpob;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Harga extends Component
{
    public $selectedCategory = 'prepaid'; // Default kategori  
    public $selectedBrand = null; // Pilihan produk berdasarkan brand  
    public $productsByBrand = []; // Daftar produk berdasarkan kategori  
    public $products = []; // Data produk untuk ditampilkan di tabel  

    public function mount()
    {
        $this->loadProductsByBrand();
        $this->loadProducts();
    }

    public function updatedSelectedCategory()
    {
        $this->selectedBrand = null; // Reset pilihan produk saat kategori berubah  
        $this->loadProductsByBrand();
        $this->loadProducts();
    }

    public function updatedSelectedBrand()
    {
        $this->loadProducts();
    }

    private function loadProductsByBrand()
    {
        // Mengambil daftar produk berdasarkan kategori (prepaid atau postpaid)  
        $this->productsByBrand = Category::where('order', $this->selectedCategory)
            ->select('brand', 'name')
            ->groupBy('brand', 'name')
            ->get();
    }

    private function loadProducts()
    {
        // Mengambil data produk sesuai kategori dan brand yang dipilih
        $query = ProductPpob::query();

        if ($this->selectedCategory) {
            $query->whereHas('category', function ($query) {
                $query->where('order', $this->selectedCategory); // Filter berdasarkan kategori
            });
        }

        if ($this->selectedBrand) {
            $query->where('brand', $this->selectedBrand); // Filter berdasarkan brand
        }

        $this->products = $query->select('name', 'mitra_price', 'cust_price', 'healthy')->get();
    }

    public function render()
    {
        return view('livewire.harga');
    }
}
