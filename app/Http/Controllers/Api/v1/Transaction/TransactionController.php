<?php
   
namespace App\Http\Controllers\Api\v1\Transaction;
   

use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransactionReportRequest;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use App\Services\Transaction\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

   
class TransactionController extends BaseController
{
    /**
     * @var transactionService
     */
    protected $transactionService;

    /**
     * TransactionService Constructor
     *
     * @param TransactionService $transactionService
     *
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = $this->transactionService->viewTransactions();
    
        return $this->sendResponse(TransactionResource::collection($transactions), 'Transactions retrieved successfully.');
    }

    /**
     * Store a new transaction.
     */
    public function store(TransactionRequest $request): JsonResponse
    {  
        $validatedData = $request->validated();

        $transaction = $this->transactionService->createTransaction($validatedData);

        return $this->sendResponse(new TransactionResource($transaction), 'Transaction created successfully.');
    }

     /**
     * Generate report.
     */
    public function generateReport(TransactionReportRequest $request): JsonResponse
    {  
        $validatedData = $request->validated();

        $payment = $this->transactionService->generateReport($validatedData);

        return $payment;
    }



}