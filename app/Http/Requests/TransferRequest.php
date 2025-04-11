<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Autoriza a requisição para continuar.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para transferência de saldo.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', 'min:0.01'], // Valor da transferência
            'payer' => ['required', 'integer'], // ID do pagador
            'payee' => ['required', 'integer', 'different:payer'], // ID do recebedor (não pode ser igual ao pagador)
        ];
    }
}
