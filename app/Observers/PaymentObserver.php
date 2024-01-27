<?php

namespace App\Observers;

use App\Models\Payment\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $this->updateTransactionStaus($payment);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
 
    private function updateTransactionStaus($payment)
    {
        $paymentAmount= Payment::where('transaction_id', $payment->transaction_id)->sum('amount'); 
        $transactionTotalAmount= $payment->transaction->total_amount;
        $dueDate = Carbon::parse($payment->transaction->due_on)->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        if($paymentAmount >= $transactionTotalAmount){
            $payment->transaction->update(['status_id' =>TRX_STATUS_PAID_ID]);
        }
        else{
            if($currentDate < $dueDate) {
                $payment->transaction->update(['status-id' =>TRX_STATUS_OUTSTANDING_ID]);
            }
            else {
                $payment->transaction->update(['status_id' =>TRX_STATUS_OVERDUE_ID]);
            }
        }

    }
}
