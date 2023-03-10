<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Wilayah;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'no_punggung' => $this->faker->sentence[10],
            'nama' => $this->faker->name,
            'alamat' => $this->faker->address[10],
            'npwp' => $this->faker->sentence[10],
            'no_hp' => $this->faker->sentence[10],
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'id_jabatan' => $this->faker->numberBetween(4,4),
            // 'id_wilayah' => $this->faker->numberBetween(1, 15),
            'id_atasan' => $this->faker->numberBetween(12, 61),
        ];
    }
}
