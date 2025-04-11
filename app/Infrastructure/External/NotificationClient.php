<?php

namespace Infrastructure\External;

use Domains\Wallet\Entities\User;
use Illuminate\Support\Facades\Http;

class NotificationClient
{
    /**
     * Envia uma notificação para o usuário informando que recebeu uma transferência.
     *
     * @param User $user
     * @return void
     */
    public function notify(User $user): void
    {
        Http::post('https://util.devi.tools/api/v1/notify', [
            'user' => $user->email,
            'message' => 'Você recebeu uma transferência!',
        ]);
    }
}
