<?php

namespace Infrastructure\External;

use Illuminate\Support\Facades\Http;

class AuthorizationClient
{
    /**
     * Verifica se a transação está autorizada por serviço externo.
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        $response = Http::get('https://util.devi.tools/api/v2/authorize');

        return $response->successful() && data_get($response->json(), 'data.authorization', false) === true;
    }
}
