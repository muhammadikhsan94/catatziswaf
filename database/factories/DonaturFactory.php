<?php

namespace Database\Factories;

use App\Models\Donatur;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonaturFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donatur::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->name,
            'alamat' => $this->faker->address,
            'npwp' => $this->faker->phoneNumber,
            'no_hp' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
