<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasUuids;

    protected $table = 'tickets';
    protected $fillable = ['event_id', 'name', 'total', 'price'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
