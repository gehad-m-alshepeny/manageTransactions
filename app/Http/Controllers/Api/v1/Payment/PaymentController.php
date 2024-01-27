<?php
   
namespace App\Http\Controllers\Api\v1\Payment;
   

use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

   
class PaymentController extends BaseController
{
    /**
     * @var paymentService
     */
    protected $paymentService;

    /**
     * PaymentService Constructor
     *
     * @param PaymentService $paymentService
     *
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Store a new payment.
     */
    public function store(PaymentRequest $request): JsonResponse
    {  
        $validatedData = $request->validated();

        $payment = $this->paymentService->recordPayment($validatedData);

        return $this->sendResponse(new PaymentResource($payment), 'Payment created successfully.');
    }

}