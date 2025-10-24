<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasUuids;

    protected $table = 'users';
    protected $fillable = ['email', 'password', 'name', 'gender', 'phone', 'address', 'role', 'profile_picture'];

    public function resetPasswords()
    {
        return $this->hasMany(ResetPassword::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
