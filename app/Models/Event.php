<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUuids;

    protected $table = 'events';
    protected $fillable = ['name', 'time_start', 'time_end'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
