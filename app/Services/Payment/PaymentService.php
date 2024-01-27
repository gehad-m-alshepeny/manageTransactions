<?php

namespace App\Services\Payment;

use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Services\Service;
use Defuse\Crypto\Crypto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PaymentService extends Service
{
    /**
     * @var $paymentRepository
     */
    protected $paymentRepository;

    /**
     * PaymentRepository constructor.
     *
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
    * Store Payment.
    *
    * @return String
    */
    public function recordPayment(array $data)
    {
        $this->checkUserLogged();

        $this->authorize('record_payment');
        
        return $this->paymentRepository->recordPayment($data);
    }
    
}
