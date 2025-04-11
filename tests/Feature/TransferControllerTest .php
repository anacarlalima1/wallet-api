<?php

namespace Tests\Feature;

use Domains\Wallet\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_transfer_money_between_users()
    {
        $payer = User::factory()->create(['balance' => 100.00]);
        $payee = User::factory()->create(['balance' => 50.00]);

        $response = $this->post('/transfer', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => 50.00,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Transferência realizada com sucesso.',
        ]);

        $payer->refresh();
        $payee->refresh();
        $this->assertEquals(50.00, $payer->balance);
        $this->assertEquals(100.00, $payee->balance);
    }

    public function test_it_returns_error_when_balance_is_insufficient()
    {
        $payer = User::factory()->create(['balance' => 30.00]);
        $payee = User::factory()->create(['balance' => 50.00]);

        $response = $this->post('/transfer', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => 50.00,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Saldo insuficiente.',
        ]);
    }
}
