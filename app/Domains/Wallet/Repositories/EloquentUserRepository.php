<?php

namespace Domains\Wallet\Repositories;

use Domains\Wallet\Entities\User;
use Domains\Wallet\Repositories\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    /**
     * Retorna um usuário pelo ID.
     *
     * @param int $userId
     * @return User|null
     */
    public function find(int $userId): ?User
    {
        return $this->model->find($userId);
    }

    /**
     * Atualiza o saldo de um usuário.
     *
     * @param User $user
     * @param float $newBalance
     * @return void
     */
    public function updateBalance(User $user, float $newBalance): void
    {
        $user->balance = $newBalance;
        $user->save();
    }
}
