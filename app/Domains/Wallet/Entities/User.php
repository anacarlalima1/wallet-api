<?php

namespace Domains\Wallet\Entities;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'cpf_cnpj', 'password', 'balance', 'type',
    ];

    protected $hidden = ['password'];
    protected $casts = [
        'balance' => 'float',
        'type' => 'string',
    ];
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
