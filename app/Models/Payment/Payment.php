<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Transaction\Transaction;

class Payment extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'transaction_id',
        'paid_on',
        'details',
        'created_by',
    ];

    /***********************
     * Ralationships
     **********************/
     public function createdBy(): BelongsTo
     {
         return $this->belongsTo(User::class,'created_by');
     }

     public function transaction(): BelongsTo
     {
         return $this->belongsTo(Transaction::class,'transaction_id');
     }


}
