<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TransactionResource extends JsonResource
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
            'vat' => $this->vat,
            'is_vat_inclusive' => $this->is_vat_inclusive,
            'total_amount' => $this->total_amount,
            'payer' => $this->payerBy->name,
            'status' => $this->transactionStatus->name,
            'createdBy'=> $this->createdBy->name,
            'due_on' => Carbon::parse($this->due_on)->format('Y-m-d') ,
        ];
    }
}
