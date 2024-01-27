<?php

namespace App\Repositories\Transaction;
/**
* Interface TransactionRepositoryInterface
* @package App\Repositories\Transaction
*/
interface TransactionRepositoryInterface
{
    public function createTransaction(array $data);

    public function viewTransactions();

    public function generateReport(array $data);

}