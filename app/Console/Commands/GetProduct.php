<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\DigiFlazzController;
use App\Models\Category;
use App\Models\ProductPpob;
use App\Models\Profit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync DigiFlazz products and categories with the local database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $digiflazzController = new DigiFlazzController();
        $priceListResponse = $digiflazzController->getPriceList();

        if ($priceListResponse['result']) {
            $priceList = $priceListResponse['data'];
            $profits = Profit::whereIn('key', ['customer', 'mitra'])
                ->select('key', 'value')
                ->get()
                ->pluck('value', 'key');

            // Siapkan array untuk bulk insert/update
            $productsToInsert = [];
            $productsToUpdate = [];

            // Ambil semua produk yang ada sekali saja
            $existingProducts = ProductPpob::select('code', 'name', 'note', 'brand', 'price', 'mitra_price', 'cust_price', 'status', 'type', 'provider', 'label', 'healthy')
                ->get()
                ->keyBy('code');

            foreach ($priceList as $product) {
                $priceMitraMargin = $product['price'] * (1 + $profits['mitra'] / 100);
                $priceBasicMargin = $product['price'] * (1 + $profits['customer'] / 100);

                // Cek dan tambahkan kategori jika belum ada
                Category::firstOrCreate([
                    'brand' => $product['brand'],
                    'name' => $product['brand'],
                    'type' => $product['otype'],
                    'order' => strtolower($product['prepost']),
                    'real' => $product['category']
                ]);

                $productData = [
                    'name' => $product['name'],
                    'code' => $product['code'],
                    'note' => $product['note'],
                    'brand' => $product['brand'],
                    'price' => $product['price'],
                    'mitra_price' => $priceMitraMargin,
                    'cust_price' => $priceBasicMargin,
                    'discount' => $product['discount'],
                    'status' => $product['status'],
                    'type' => $product['type'],
                    'provider' => 'DigiFlazz',
                    'label' => $product['label'],
                    'healthy' => $product['healthy'],
                    'updated_at' => now()
                ];

                if (isset($existingProducts[$product['code']])) {
                    $existingProduct = $existingProducts[$product['code']];

                    // Check if update needed
                    if ($this->needsUpdate($existingProduct, $productData)) {
                        $productsToUpdate[] = $productData;

                        $this->logProductUpdate($existingProduct, $productData);
                    } else {
                        $this->info("[*] {$product['name']} {Data masih sama}");
                    }
                } else {
                    $productsToInsert[] = $productData;
                    $this->logNewProduct($productData);
                }
            }

            // Bulk operations
            DB::transaction(function () use ($productsToInsert, $productsToUpdate) {

                // Bulk insert new products
                if (!empty($productsToInsert)) {
                    ProductPpob::insert($productsToInsert);
                }

                // Bulk update existing products
                if (!empty($productsToUpdate)) {
                    foreach ($productsToUpdate as $product) {
                        ProductPpob::where('code', $product['code'])->update($product);
                    }
                }
            });

            $this->info('DigiFlazz price list synced successfully.');
        } else {
            $this->error('Error syncing DigiFlazz price list: ' . $priceListResponse['message']);
            Log::error('Error syncing DigiFlazz price list: ' . $priceListResponse['message']);
        }

        return 0;
    }

    private function needsUpdate($existing, $new): bool
    {
        return $existing->mitra_price != $new['mitra_price'] ||
            $existing->cust_price != $new['cust_price'] ||
            $existing->price != $new['price'] ||
            $existing->name != $new['name'] ||
            $existing->note != $new['note'] ||
            $existing->brand != $new['brand'] ||
            $existing->status != $new['status'] ||
            $existing->type != $new['type'] ||
            $existing->provider != $new['provider'] ||
            $existing->label != $new['label'] ||
            $existing->healthy != $new['healthy'];
    }

    private function logProductUpdate($old, $new)
    {
        $this->info("[+] {$new['name']} {Berhasil diupdate}");
        $this->info("Type: {$new['type']}");
        $this->info("Status: {$old->status} -> {$new['status']}");
        $this->info("Harga Pusat: {Rp. " . nominal($old->price, 'IDR') . "} -> {Rp. " . nominal($new['price'], 'IDR') . "}");
        $this->info("Harga Mitra: {Rp. " . nominal($old->mitra_price, 'IDR') . "} -> Rp. " . nominal($new['mitra_price'], 'IDR'));
        $this->info("Harga Customer: {Rp. " . nominal($old->cust_price, 'IDR') . "} -> Rp. " . nominal($new['cust_price'], 'IDR'));
        $this->info('<hr>');
    }

    private function logNewProduct($product)
    {
        $this->info("[+] {$product['name']} {Berhasil ditambahkan}");
        $this->info("Type: {$product['type']}");
        $this->info("Status: {$product['status']}");
        $this->info("Harga Pusat: {$product['price']}");
        $this->info("Harga Basic: Rp. " . nominal($product['cust_price'], 'IDR'));
        $this->info('<hr>');
    }
}
