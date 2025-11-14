<?php

namespace App\Models;

use CodeIgniter\Model;
use Faker\Factory as Faker;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    function getChart()
    {

        $charts = $this->select('product, sum(qty) as qty')->groupBy('product')->limit(5)->findAll();
        $labels = array_column($charts, 'product');
        $values = array_column($charts, 'qty');
        $data = [
            'labels' => $labels,
            'values' => $values,
        ];
        return $data;
    }

    function generateData()
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
        db_connect()->table('transactions')->insertBatch($data);
    }
}
