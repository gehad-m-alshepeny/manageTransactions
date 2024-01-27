<?php

namespace Database\Seeders;

use App\Models\Transaction\TransactionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void  
    {
        TransactionStatus::updateOrCreate(['id' => TRX_STATUS_OUTSTANDING_ID], [
            'name' => 'OUTSTANDING'
        ]);

        TransactionStatus::updateOrCreate(['id' => TRX_STATUS_PAID_ID], [
            'name' => 'PAID'
        ]);
   
        TransactionStatus::updateOrCreate(['id' => TRX_STATUS_OVERDUE_ID], [
            'name' => 'OVERDUE'
        ]);

    }
}