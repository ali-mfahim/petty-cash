<?php

namespace App\Console\Commands;

use App\Models\Collection;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LinkProductsToCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link-products-to-collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will save the products to the collection on shopify store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $collections = Collection::where("is_exported", 1)->where("is_product_exported", 0)->limit(10)->get();
            if (isset($collections) && !empty($collections) && count($collections)) {
                foreach ($collections as $i => $v) {
                    $response = addProductsToCollection($v);
                    dd($response);
                }
            }
        } catch (Exception $e) {
            $message = "Error while linking products to the collection: " . $e->getMessage() . "-file: " . $e->getFile() . "-line: " . $e->getLine();
            saveLog($message, "", "Collection", 2, []);
        }
        // 
    }
}
