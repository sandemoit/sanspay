<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SitemapGenerator::create(config('app.url'))
            ->hasCrawled(function (Url $url) {
                // Menentukan prioritas berdasarkan path  
                switch ($url->path()) {
                    case '/':
                        $url->setPriority(1.0);
                        break;
                    case '/harga-produk':
                        $url->setPriority(0.8);
                        $url->setLastModificationDate(Carbon::create('2025', '1', '10'));
                        break;
                    case '/login':
                    case '/register':
                        $url->setPriority(0.5);
                        break;
                    default:
                        $url->setPriority(0.3); // Prioritas default untuk halaman lain  
                        break;
                }

                return $url;
            })
            ->configureCrawler(function (Crawler $crawler) {
                $crawler->ignoreRobots();
            })
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
