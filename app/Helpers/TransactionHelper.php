<?php

namespace App\Helpers;

use App\Models\Transaction\Transaction;
use Carbon\Carbon;

class TransactionHelper
{

    public static function updateTransactionStatus()
    {
        $currentDate = Carbon::now();
        $outStandingTransactions= Transaction::where('status_id', TRX_STATUS_OUTSTANDING_ID)
                                              ->where('due_on','<',$currentDate)
                                              ->update(['status_id' =>TRX_STATUS_OVERDUE_ID]);
    }
}
