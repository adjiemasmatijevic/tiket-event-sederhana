<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ots extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ots';
    protected $fillable = ['name', 'phone'];

    public function tickets()
    {
        return $this->hasMany(Cart::class, 'ots_id');
    }
}
