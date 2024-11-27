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
            $collections = Collection::where("type", 1)->where("is_exported", 0)->whereNotNull("raw_data")->where('id', 5)->limit(1)->get();

            if (isset($collections) && !empty($collections) && count($collections) > 0) {
                foreach ($collections as  $value) {
                    if (isset($value->title) && !empty($value->title)) {
                        $createCollection = createUniqueCollection($value);
                        if ($createCollection->success == false) {
                            $this->info($createCollection->message);
                        }
                        if ($createCollection->success == true) {
                            if (isset($createCollection->data['data']['collectionCreate']['collection']) && !empty($createCollection->data['data']['collectionCreate']['collection'])) {
                                $data = (object) $createCollection->data['data']['collectionCreate']['collection'];
                                $updateCollection = $value->update([
                                    "new_gid" => $data->id ?? null,
                                    "new_title" => $data->title ?? null,
                                    "new_handle" => $data->handle ?? null,
                                    "new_raw_data" => json_encode($data) ?? null,
                                    "is_exported" => 1,
                                ]);
                                if ($updateCollection > 0) {
                                    $message = "Collection " . $data->title  . " has been exported to the new store";
                                    saveLog($message, $value->id, "Collection", 1, []);
                                    $this->info($message);
                                } else {
                                    saveLog("Something went wrong while exporting the collection", $value->id, "Collection", 2, []);
                                }
                            } else {
                                saveLog("Invalid Response from Collection Creation: " . json_encode($createCollection), $value->id, "Collection", 2, []);
                            }
                        }
                    } else {
                        $this->info("Title Not Found");
                        if (isset($createCollection->success) && $createCollection->success == false) {
                            $this->info($createCollection->message);
                            $value->update([
                                // "status" => 0,
                                "is_exported" => 0,
                            ]);
                        }
                    }
                }
            } else {
                saveLog("No collections found to be exported", null, "Collection", 3, []);
            }
        } catch (Exception $e) {
            saveLog("error while exporting collection: " . $e->getMessage(), null, "Collection", 2, []);
        }
    }
}
