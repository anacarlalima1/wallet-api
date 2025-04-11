<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use Application\UseCases\Wallet\TransferMoneyUseCase;
use Domains\Wallet\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Exception;

class TransferController extends Controller
{
    protected UserRepositoryInterface $userRepo;
    protected TransferMoneyUseCase $useCase;

    /**
     * Cria uma nova instância do controlador de transferências.
     *
     * @param TransferMoneyUseCase $useCase
     */
    public function __construct(UserRepositoryInterface $userRepo, TransferMoneyUseCase $useCase)
    {
        $this->userRepo = $userRepo;
        $this->useCase = $useCase;
    }

    /**
     * Processa uma transferência entre dois usuários.
     *
     * @param TransferRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(TransferRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $this->useCase->handle(
                $data['payer'],
                $data['payee'],
                $data['value']
            );
            $payer = $this->userRepo->find($data['payer']);
            $payee = $this->userRepo->find($data['payee']);

            return response()->json([
                'message' => 'Transferência realizada com sucesso.',
                'payer_balance' => $payer->balance,
                'payee_balance' => $payee->balance,
            ]);
        } catch (Exception $e) {
            // Retorna erro detalhado se a transferência falhar
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
