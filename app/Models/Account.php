<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'deposito_type_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depositoType()
    {
        return $this->belongsTo(DepositoType::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
