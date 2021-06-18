<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Donatur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransaksiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaksi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'no_kuitansi' => Str::random(3),
            'item' => 'NON TUNAI',
            'jumlah' => $this->faker->numberBetween(100000, 10000000),
            'bukti_transaksi' => 'jpg',
            'id_users' => $this->faker->numberBetween(62, 161),
            'id_donatur' => $this->faker->numberBetween(1, 100),
            'id_lembaga' => $this->faker->numberBetween(1, 3),
            'id_paket_zakat' => $this->faker->numberBetween(1, 21),
        ];
    }
}
