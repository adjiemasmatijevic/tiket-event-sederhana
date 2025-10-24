<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use HasUuids;

    protected $table = 'reset_passwords';
    protected $fillable = ['user_id', 'expired_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
