<?php

namespace Database\Seeders;

use App\Models\DepositoType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepositoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $depositoTypes = [
            [
                'name' => 'Deposito Bronze',
                'yearly_return' => 3.00,
            ],
            [
                'name' => 'Deposito Silver',
                'yearly_return' => 5.00,
            ],
            [
                'name' => 'Deposito Gold',
                'yearly_return' => 7.00,
            ],
        ];

        foreach ($depositoTypes as $depositoType) {
            DepositoType::create($depositoType);
        }
    }
}
