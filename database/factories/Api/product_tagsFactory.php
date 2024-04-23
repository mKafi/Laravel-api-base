<?php

namespace Database\Factories\Api;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Api\product_tags>
 */
class product_tagsFactory extends Factory
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
            'tag_for' => 'product',
            'tags' => 'tag1,tag2,lorium,ipsome, dolor, sit, amet'
        ];
    }
}
