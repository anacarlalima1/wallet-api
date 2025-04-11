<?php
namespace Tests\Unit;

use Domains\Wallet\Entities\User;
use Domains\Wallet\Repositories\UserRepositoryInterface;
use Infrastructure\External\AuthorizationClient;
use Infrastructure\External\NotificationClient;
use Application\UseCases\Wallet\TransferMoneyUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;

class TransferMoneyUseCaseTest extends TestCase
{
    protected $userRepo;
    protected $authClient;
    protected $notifyClient;
    protected $useCase;
    protected $databaseConnection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepo = Mockery::mock(UserRepositoryInterface::class);
        $this->authClient = Mockery::mock(AuthorizationClient::class);
        $this->notifyClient = Mockery::mock(NotificationClient::class);
        $this->databaseConnection = Mockery::mock(\Illuminate\Database\DatabaseManager::class);

        $this->useCase = new TransferMoneyUseCase(
            $this->userRepo,
            $this->authClient,
            $this->notifyClient,
            $this->databaseConnection
        );
    }

    public function test_handle_throws_exception_if_user_is_merchant()
{
    $payer = new User([
        'id' => 1,
        'type' => 'merchant',
        'balance' => 100.00,
    ]);
    $payee = new User([
        'id' => 2,
        'type' => 'common',
        'balance' => 50.00,
    ]);

    $this->userRepo->shouldReceive('find')
        ->with(1)
        ->andReturn($payer);

    $this->userRepo->shouldReceive('find')
        ->with(2)
        ->andReturn($payee);

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Lojistas não podem transferir.');

    $this->useCase->handle(1, 2, 50.00);
}

   public function test_handle_throws_exception_if_balance_is_insufficient()
{
    $payer = new User([
        'id' => 1,
        'type' => 'common',
        'balance' => 30.00,
    ]);

    $payee = new User([
        'id' => 2,
        'type' => 'common',
        'balance' => 50.00,
    ]);

    $this->userRepo->shouldReceive('find')
    ->with(1)
    ->andReturn($payer);

    $this->userRepo->shouldReceive('find')
    ->with(2)
    ->andReturn($payee);

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Saldo insuficiente.');

    $this->useCase->handle(1, 2, 50.00);
}

    public function test_handle_throws_exception_if_transaction_is_not_authorized()
    {
        $payer = new User([
            'id' => 1,
            'type' => 'common',
            'balance' => 100.00,
        ]);

        $payee = new User([
            'id' => 2,
            'type' => 'common',
            'balance' => 50.00,
        ]);

        $this->userRepo->shouldReceive('find')
            ->with(1)
            ->andReturn($payer);

        $this->userRepo->shouldReceive('find')
            ->with(2)
            ->andReturn($payee);

        $this->authClient->shouldReceive('isAuthorized')
            ->andReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Transação não autorizada.');

        $this->useCase->handle(1, 2, 50.00);
    }
}
