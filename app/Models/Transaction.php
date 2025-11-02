<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasUuids;

    protected $table = 'transactions';
    protected $fillable = ['user_id', 'tdi_pay_id', 'amount_total', 'net', 'status', 'category', 'expired_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if ($transaction->category === 'payment gateway') {
                $transaction->net = round($transaction->amount_total / 1.05);
            } else {
                $transaction->net = $transaction->amount_total;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
