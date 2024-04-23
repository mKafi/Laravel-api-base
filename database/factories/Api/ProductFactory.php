<?php

namespace Database\Factories\Api;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'trader_id' => 2,
            'product_id' => 'P123456',
            'product_title' => 'TestProduct',
            'wholesell_price' => rand(25,40),
            'retail_price' => rand(42,55),
            'special_price' => rand(35,50),
            'unit' => rand(20,35),
            'lot_id' => 'LT1001',
            'tags' => 'Tag one, Tag two, Tag three'
        ];
    }
}
