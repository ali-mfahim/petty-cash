<?php

namespace Database\Seeders;

use App\Models\PreDefinedContentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreDefinedContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("pre_defined_content_types")->truncate();
        $array = [
            ['title' => 'Food'],
            ['title' => 'Refreshment'],
            ['title' => 'Stationary'],
            ['title' => 'Miscellaneous'],
            ['title' => 'Others'],
        ];
        foreach ($array as $v) {
            PreDefinedContentType::create([
                "title" => $v['title'] ?? null,
            ]);
        }
    }
}
