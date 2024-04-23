<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Api\product_tags AS PrdTags;

class product_tags extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PrdTags::factory()->count(1)->create();
        /*
        DB::table('product_tags')->insert([
            'trader_id' => 2,
            'tag_for' => 'product',            
            'tags' => 'tag1,tag2,lorium,ipsome, dolor, sit, amet'
        ]);
        */
    }
}
