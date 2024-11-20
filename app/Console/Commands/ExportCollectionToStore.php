<?php

namespace App\Console\Commands;

use App\Models\Collection;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ExportCollectionToStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export-collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will export the collections that were imported from any store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $collections = Collection::where("type", 1)->where("id" ,1)->where("status", 4)->limit(1)->get();
            if (isset($collections) && !empty($collections) && count($collections) > 0) {
                foreach ($collections as $index => $value) {
                    if (isset($value->title) && !empty($value->title)) {
                        $checkExist = checkCollectionExistsByHandle($value->title, $value->export_store_id);
                        dd($checkExist);
                    } else {
                        $this->info("Title Not Found");
                    }
                }
            } else {
                saveLog("NO collections found to be exported", null, "Collection", 3, []);
            }
        } catch (Exception $e) {
            saveLog("error while exporting collection: " . $e->getMessage(), null, "Collection", 2, []);
        }
    }
}
