<?php

namespace Database\Seeders;

use App\Models\PreDefinedContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreDefinedKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("pre_defined_contents")->truncate();
        $array = [
            ["title" => "Roti ", "type" => 1],
            ["title" => "Salan", "type" => 1],
            ["title" => "Biryani ", "type" => 1],
            ["title" => "Tea", "type" => 1],
            ["title" => "Shake", "type" => 1],
            ["title" => "Lunch", "type" => 1],
            ["title" => "Dinner", "type" => 1],
            ["title" => "Breakfast", "type" => 1],
            ["title" => "Somosa", "type" => 1],
            ["title" => "Roll", "type" => 1],
            ["title" => "Kachori", "type" => 1],
            ["title" => "Petrol", "type" => 4],

        ];
        foreach ($array as $v) {
            PreDefinedContent::create([
                "title" => $v['title'] ?? null,
                "type" => $v['type'] ?? null,
            ]);
        }
    }
}
