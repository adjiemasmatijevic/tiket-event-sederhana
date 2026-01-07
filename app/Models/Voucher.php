<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $fillable = [
        'voucher_code',
        'type',
        'value',
        'limit',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'date',
    ];

}
