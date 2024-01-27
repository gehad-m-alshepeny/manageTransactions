<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TransactionResource;
use Carbon\Carbon;


class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'createdBy'=> $this->createdBy->name,
            'paid_on' => Carbon::parse($this->due_on)->format('Y-m-d') ,
            'details' => $this->details,
            'transaction' => (new TransactionResource($this->transaction)),
        ];
    }
}
