<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory as Faker;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $data = [];

        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                // 'product'    => $faker->word(),
                'product' => $faker->randomElement(['Keyboard', 'Mouse', 'Monitor', 'Printer', 'Laptop']),
                'qty'        => $faker->numberBetween(1, 100),
                'date'       => $faker->date(),
                'created_at' => $faker->date('Y-m-d H:i:s'),
                'updated_at' => $faker->date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ];
        }

        // Insert batch ke tabel transactions
        $this->db->table('transactions')->insertBatch($data);
    }
}
