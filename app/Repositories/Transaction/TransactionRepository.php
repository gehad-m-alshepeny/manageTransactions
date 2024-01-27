<?php

namespace App\Repositories\Transaction;

use App\Repositories\Transaction\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction\Transaction;
use Carbon\Carbon;
use App\Helpers\TransactionHelper;

class TransactionRepository implements TransactionRepositoryInterface
{
   
    public function createTransaction(array $data)
    {
        $data['created_by'] = Auth::user()->id;
        $data['status_id'] = $this->getTransactionStatus($data['due_on']);
        $data['total_amount'] = ($data['is_vat_inclusive']) ? $data['amount'] 
                                                            : (($data['amount'] * $data['vat'])/100) + $data['amount'];
        $data['remaining_amount'] = $data['total_amount'];

        return Transaction::create($data);
    }

    public function viewTransactions()
    {
        TransactionHelper::updateTransactionStatus();

        return Transaction::with('transactionStatus','createdBy','payerBy')
                          ->verified()->latest()->paginate(10);
    }

    public function generateReport(array $data)
    {
        TransactionHelper::updateTransactionStatus();
        
        $result = Transaction::select(
            DB::raw('MONTH(due_on) as month'),
            DB::raw('YEAR(due_on) as year'),
            DB::raw('COALESCE(SUM(total_amount - remaining_amount), 0) as paid'),
            DB::raw('COALESCE(SUM(CASE WHEN status_id = '.TRX_STATUS_OVERDUE_ID.' THEN remaining_amount ELSE 0 END), 0) as overdue'),
            DB::raw('COALESCE(SUM(CASE WHEN status_id = '.TRX_STATUS_OUTSTANDING_ID.' THEN remaining_amount ELSE 0 END), 0) as outstanding'),
            DB::raw('SUM(total_amount) as total'),
        )
        ->whereDate('due_on', '>=', $data['start_date'])->whereDate('due_on', '<=', $data['end_date'])
        ->groupBy(DB::raw('YEAR(due_on), MONTH(due_on)'))
        ->orderBy(DB::raw('YEAR(due_on), MONTH(due_on)'))
        ->get();

        return response()->json($result);  
    }

    private function getTransactionStatus($dueDate)
    {
        $status = TRX_STATUS_OUTSTANDING_ID;

        $currentDate = Carbon::now()->format('Y-m-d');

        $dueDate = Carbon::parse($dueDate)->format('Y-m-d');

        if($dueDate < $currentDate){
           $status = TRX_STATUS_OVERDUE_ID;
        }

       return $status;
    }
    
}