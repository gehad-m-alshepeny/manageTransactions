<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Payment\Payment;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'payer',
        'due_on',
        'status_id',
        'vat',
        'is_vat_inclusive',
        'total_amount',
        'remaining_amount',
        'created_by',
    ];

     /***********************
     * Ralationships
     **********************/

     public function transactionStatus() : BelongsTo
     {
         return $this->belongsTo(TransactionStatus::class, 'status_id');
     }

     public function payerBy(): BelongsTo
     {
         return $this->belongsTo(User::class,'payer');
     }

     public function createdBy(): BelongsTo
     {
         return $this->belongsTo(User::class,'created_by');
     }

     public function payments(): HasMany
     {
         return $this->hasMany(Payment::class, 'transaction_id');
     }

    /***********************
     * Scopes
     **********************/
    public function scopeVerified($query)
    {
       if(auth()->check() && auth()->user()->role_id != ADMIN) 
         return $query->where('payer',auth()->user()->id);
    }
}
