<?php

namespace Application\UseCases\Wallet;

use App\Jobs\TransferMoneyJob;
use Illuminate\Database\DatabaseManager;
use Domains\Wallet\Repositories\UserRepositoryInterface;
use Infrastructure\External\AuthorizationClient;
use Infrastructure\External\NotificationClient;
use RuntimeException;

class TransferMoneyUseCase
{
    protected UserRepositoryInterface $userRepo;
    protected AuthorizationClient $authClient;
    protected NotificationClient $notifyClient;
    protected DatabaseManager $databaseConnection;

    public function __construct(
        UserRepositoryInterface $userRepo,
        AuthorizationClient $authClient,
        NotificationClient $notifyClient,
        DatabaseManager $databaseConnection
    ) {
        $this->userRepo = $userRepo;
        $this->authClient = $authClient;
        $this->notifyClient = $notifyClient;
        $this->databaseConnection = $databaseConnection;
    }

    /**
     * Executa a transferência entre dois usuários.
     *
     * @param int $payerId ID do pagador
     * @param int $payeeId ID do recebedor
     * @param float $amount Valor da transferência
     * @throws RuntimeException Se o lojista tentar transferir, saldo for insuficiente ou a operação não for autorizada
     */
    public function handle(int $payerId, int $payeeId, float $amount): void
    {
        $payer = $this->userRepo->find($payerId);
        $payee = $this->userRepo->find($payeeId);

        if ($payer->type === 'merchant') {
            throw new RuntimeException('Lojistas não podem transferir.');
        }

        if ($payer->balance < $amount) {
            throw new RuntimeException('Saldo insuficiente.');
        }

        if (! $this->authClient->isAuthorized()) {
            throw new RuntimeException('Transação não autorizada.');
        }

        $this->databaseConnection->transaction(function () use ($payer, $payee, $amount) {
            $this->userRepo->updateBalance($payer, $payer->balance - $amount);
            $this->userRepo->updateBalance($payee, $payee->balance + $amount);
        });

        // TransferMoneyJob::dispatch($payerId, $payeeId, $amount);

        $this->notifyClient->notify($payee);
    }
}
