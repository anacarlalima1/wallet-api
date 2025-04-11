<?php

namespace App\Jobs;

use Domains\Wallet\Repositories\UserRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Infrastructure\External\NotificationClient;

class TransferMoneyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payerId;
    protected $payeeId;
    protected $amount;

    /**
     * Cria uma nova instância do job.
     *
     * @param int $payerId
     * @param int $payeeId
     * @param float $amount
     * @return void
     */

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $payerId, int $payeeId, float $amount)
    {
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepositoryInterface $userRepo, NotificationClient $notifyClient)
    {
        try {
            $payer = $userRepo->find($this->payerId);
            $payee = $userRepo->find($this->payeeId);

            if ($payer->balance >= $this->amount) {
                $payer->balance -= $this->amount;
                $payee->balance += $this->amount;

                $userRepo->updateBalance($payer, $payer->balance);
                $userRepo->updateBalance($payee, $payee->balance);

                $notifyClient->notify($payee);
            }
        } catch (\Exception $e) {
            Log::error('Erro no job de transferência: ' . $e->getMessage());
        }
    }
}
