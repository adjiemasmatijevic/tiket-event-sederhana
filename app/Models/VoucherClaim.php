<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Voucher;

class VoucherClaim extends Model
{
    protected $table = 'voucher_claims';

    protected $fillable = [
        'voucher_id',
        'user_id',
    ];


    protected $keyType = 'string';
    public $incrementing = false;

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
