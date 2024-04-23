<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Api\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Product::factory()->count(20)->create();
        /* 
        DB::table('products')->insert([
            'trader_id' => Str::random(10),
            'product_id' => Str::random(10),
            'product_title' => Str::random(6),
            'wholesell_price' => 9,
            'retail_price' => 12,
            'special_price' => 10,
            'unit' => '5',
            'lot_id' => '123',
            'tags' => 'tag1,tag2'
        ]);
        */
        Product::factory()
            ->count(5)            
            ->create();
    }
}
