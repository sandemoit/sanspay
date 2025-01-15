<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\DigiFlazzController;
use App\Models\Category;
use App\Models\ProductPpob;
use App\Models\Profit;
use Illuminate\Console\Command;
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
            $profits = Profit::whereIn('key', ['customer', 'mitra'])->pluck('value', 'key');

            // Loop through the price list and insert/update products
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

                // Prepare product data  
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
                ];

                // Check if product exists  
                $existingProduct = ProductPpob::where('code', $product['code'])->first();

                if ($existingProduct) {
                    // Check for changes
                    if (
                        $existingProduct->mitra_price != $priceMitraMargin ||
                        $existingProduct->cust_price != $priceBasicMargin ||
                        $existingProduct->price != $product['price'] ||
                        $existingProduct->name != $product['name'] ||
                        $existingProduct->note != $product['note'] ||
                        $existingProduct->brand != $product['brand'] ||
                        $existingProduct->status != $product['status'] ||
                        $existingProduct->type != $product['type'] ||
                        $existingProduct->provider != 'DigiFlazz' ||
                        $existingProduct->label != $product['label'] ||
                        $existingProduct->healthy != $product['healthy']
                    ) {
                        $existingProduct->update([
                            'code' => $product['code'],
                            'name' => $product['name'],
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
                            'updated_at' => now(),
                        ]);

                        $this->info("[+] {$product['name']} {Berhasil diupdate}");
                        $this->info("Type: {$product['type']}");
                        $this->info("Status: {$existingProduct->status} -> {$product['status']}");
                        $this->info("Harga Pusat: {Rp. " . nominal($existingProduct->price, 'IDR') . "} -> {Rp. " . nominal($product['price'], 'IDR') . "}");
                        $this->info("Harga Mitra: {Rp. " . nominal($existingProduct->mitra_price, 'IDR') . "} -> Rp. " . nominal($priceMitraMargin, 'IDR'));
                        $this->info("Harga Customer: {Rp. " . nominal($existingProduct->cust_price, 'IDR') . "} -> Rp. " . nominal($priceBasicMargin, 'IDR'));
                        $this->info('<hr>');
                    } else {
                        $this->info("[*] {$product['name']} {Data masih sama}");
                        $this->info('<hr>');
                    }
                } else {
                    $productsToInsert[] = $productData;

                    $this->info("[+] {$productData['name']} {Berhasil ditambahkan}");
                    $this->info("Type: {$productData['type']}");
                    $this->info("Status: {$productData['status']}");
                    $this->info("Harga Pusat: {$productData['price']}");
                    $this->info("Harga Basic: Rp. " . nominal($productData['cust_price'], 'IDR'));
                    $this->info('<hr>');
                }
            }

            // Insert new products  
            if (!empty($productsToInsert)) {
                ProductPpob::insert($productsToInsert);
            }

            $this->info('DigiFlazz price list synced successfully.');
        } else {
            $this->error('Error syncing DigiFlazz price list: ' . $priceListResponse['message']);
            Log::error('Error syncing DigiFlazz price list: ' . $priceListResponse['message']);
        }

        return 0;
    }
}
