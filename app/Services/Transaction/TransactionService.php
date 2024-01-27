<?php

namespace App\Services\Transaction;

use App\Repositories\Transaction\TransactionRepository;
use App\Services\Service;
use Defuse\Crypto\Crypto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class TransactionService extends Service
{
    /**
     * @var $transactionRepository
     */
    protected $transactionRepository;

    /**
     * TransactionRepository constructor.
     *
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }
    

    /**
    * Store Transaction.
    *
    * @return String
    */
    public function createTransaction(array $data)
    {
        $this->checkUserLogged();

        $this->authorize('create_transaction');
        
        return $this->transactionRepository->createTransaction($data);
    }

     /**
     * Get all transactions.
     *
     * @return String
     */
    public function viewTransactions()
    {
        $this->checkUserLogged();

        $this->authorize('view_transactions');
        
        return $this->transactionRepository->viewTransactions();
    }

     /**
    * Generate Report.
    *
    * @return String
    */
    public function generateReport(array $data)
    {
        $this->checkUserLogged();

        $this->authorize('generate_reports');
        
        return $this->transactionRepository->generateReport($data);
    }



    
}
