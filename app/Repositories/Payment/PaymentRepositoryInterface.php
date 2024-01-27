<?php

namespace App\Repositories\Payment;
/**
* Interface PaymentRepositoryInterface
* @package App\Repositories\Payment
*/
interface PaymentRepositoryInterface
{
    public function recordPayment(array $data);

}