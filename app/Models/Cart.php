<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasUuids;

    protected $table = 'carts';
    protected $fillable = ['user_id', 'ticket_id', 'ticket_no', 'presence', 'transaction_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
