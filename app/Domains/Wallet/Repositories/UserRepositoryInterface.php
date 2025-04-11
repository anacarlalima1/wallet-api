<?php

namespace Domains\Wallet\Repositories;

use Domains\Wallet\Entities\User;

interface UserRepositoryInterface
{
    public function find(int $userId): ?User;
    public function updateBalance(User $user, float $newBalance): void;
}
