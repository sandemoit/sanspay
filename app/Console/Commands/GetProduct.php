<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\DigiFlazzController;
use App\Models\Category;
use App\Models\ProductPpob;
use App\Models\Profit;
use Illuminate\Console\Command;

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
            // Loop through the price list and insert/update products
            foreach ($priceList as $product) {
                $profit = Profit::where('key', 'customer')->value('value');
                $priceBasicMargin = $product['price'] + ($product['price'] * $profit / 100); // Harga Basic dengan margin
                $status = $product['status'] == true ? 'available' : 'empty';

                // Cek dan tambahkan kategori jika belum ada
                $category = Category::firstOrCreate([
                    'code' => $product['brand'],
                    'name' => $product['brand'],
                    'type' => $product['type'],
                    'order' => strtolower($product['prepost'])
                ]);

                // Cek apakah produk sudah ada di database
                $existingProduct = ProductPpob::where('code', $product['code'])->first();

                if ($existingProduct) {
                    // Update data produk jika ada perubahan
                    $changes = [];
                    if (
                        $existingProduct->cust_price != $priceBasicMargin ||
                        $existingProduct->price != $product['price'] ||
                        $existingProduct->name != $product['name'] ||
                        $existingProduct->code != $product['code'] ||
                        $existingProduct->type != $product['type'] ||
                        $existingProduct->brand != $product['brand'] ||
                        $existingProduct->status != $status
                    ) {
                        $existingProduct->update([
                            'name' => $product['name'],
                            'code' => $product['code'],
                            'note' => $product['note'],
                            'brand' => $product['brand'],
                            'price' => $product['price'],
                            'cust_price' => $priceBasicMargin,
                            'status' => $status,
                            'type' => $product['type'],
                            'provider' => 'DigiFlazz'
                        ]);

                        print '<font color="green"><pre>';
                        print "[+] {$product['name']} {Berhasil diupdate}<br>";
                        print "Type: {$product['type']}<br>";
                        print "Status: {$existingProduct->status} -> {$status}<br>";
                        print "Harga Pusat: {Rp. " . nominal($existingProduct->price, 'IDR') . "} -> {Rp. " . nominal($product['price'], 'IDR') . "}<br>";
                        print "Harga Customer: {Rp. " . nominal($existingProduct->cust_price, 'IDR') . "} -> Rp. " . nominal($priceBasicMargin, 'IDR') . "<br>";
                        print '</pre></font><hr>';
                    } else {
                        print '<font color="red"><pre>';
                        print "[*] {$product['name']} {Data masih sama}<br>";
                        print '</pre></font><hr>';
                    }
                } else {
                    // Jika produk tidak ada, tambahkan data produk baru
                    $status = $product['status'] == true ? 'available' : 'empty';;
                    $newProduct = ProductPpob::create([
                        'name' => $product['name'],
                        'code' => $product['code'],
                        'note' => $product['note'],
                        'brand' => $product['brand'],
                        'price' => $product['price'],
                        'cust_price' => $priceBasicMargin,
                        'status' => $status,
                        'type' => $product['type'],
                        'provider' => 'DigiFlazz'
                    ]);

                    print '<font color="green"><pre>';
                    print "[+] {$newProduct->name} {Berhasil ditambahkan}<br>";
                    print "Type: {$newProduct->type}<br>";
                    print "Status: {$newProduct->status}<br>";
                    print "Harga Pusat: {$newProduct->price}<br>";
                    print "Harga Basic: Rp. " . nominal($priceBasicMargin, 'IDR') . "<br>";
                    print '</pre></font><hr>';
                }
            }

            $this->info('DigiFlazz price list synced successfully.');
        } else {
            $this->error('Error syncing DigiFlazz price list: ' . $priceListResponse['message']);
        }

        return 0;
    }
}
