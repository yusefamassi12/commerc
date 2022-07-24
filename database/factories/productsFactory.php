<?php

namespace Database\Factories;
use App\Models\products;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class productsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $product_name= $this->faker->unique()->words($nb=2,$asText=true);
        return [
            'name' => $product_name,
            'regularprice'=>$this->faker->numberBetween(10,500),
            'categoryid' =>$this->faker->numberBetween(1,5)
        ];
    }
}
