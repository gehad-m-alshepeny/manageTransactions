<?php

namespace App\Repositories\Payment;

use App\Repositories\Payment\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Payment\Payment;
use Illuminate\Validation\ValidationException;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentRepository implements PaymentRepositoryInterface
{
   
    public function recordPayment(array $data)
    {

        $data['created_by'] = Auth::user()->id;
        $transaction = Transaction::findOrFail($data['transaction_id']);
        $totalPaymentsOnTransaction = Payment::where('transaction_id', $data['transaction_id'])->sum('amount');
        $totalPaymentAmount = $data['amount'] + $totalPaymentsOnTransaction ;
        $remainingAmount = $transaction->remaining_amount - $data['amount'];

        if($transaction->status_id == TRX_STATUS_PAID_ID)
         throw ValidationException::withMessages(["Already paid."]);

        if($data['amount'] > $remainingAmount)
         throw ValidationException::withMessages(["Invalid amount, Remaining.".$transaction->remaining_amount]);

         $payment = Payment::create($data);
         $transaction->update(['remaining_amount' => $remainingAmount]);

        return $payment;
    }
  
}