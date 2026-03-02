<?php

namespace Tests\Feature;

use App\Mail\AbandonedCartMail;
use App\Models\AbandonedCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AbandonedCartEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_abandoned_cart_email_can_be_created()
    {
        // Criar um utilizador
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        // Criar um carrinho abandonado
        $abandonedCart = AbandonedCart::create([
            'user_id' => $user->id,
            'cart_data' => [
                '1' => [
                    'name' => 'Produto Teste',
                    'price' => 50.00,
                    'quantity' => 2,
                    'image' => 'test.jpg'
                ],
                '2' => [
                    'name' => 'Produto Teste 2',
                    'price' => 30.00,
                    'quantity' => 1,
                    'image' => 'test2.jpg'
                ]
            ]
        ]);

        // Verificar que o carrinho foi criado com token
        $this->assertNotNull($abandonedCart->token);
        $this->assertNull($abandonedCart->email_sent_at);
        $this->assertNull($abandonedCart->recovered_at);
    }

    public function test_abandoned_cart_email_is_sent()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $abandonedCart = AbandonedCart::create([
            'user_id' => $user->id,
            'cart_data' => [
                '1' => [
                    'name' => 'Produto Teste',
                    'price' => 50.00,
                    'quantity' => 2,
                    'image' => 'test.jpg'
                ]
            ]
        ]);

        // Enviar o email
        Mail::to($user->email)->send(new AbandonedCartMail($abandonedCart));

        // Verificar que o email foi enviado
        Mail::assertSent(AbandonedCartMail::class, function ($mail) use ($user, $abandonedCart) {
            return $mail->hasTo($user->email) &&
                   $mail->abandonedCart->id === $abandonedCart->id;
        });
    }

    public function test_abandoned_cart_command_sends_emails()
    {
        Mail::fake();

        // Criar utilizador
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Criar carrinho abandonado (sem email enviado)
        $abandonedCart = AbandonedCart::create([
            'user_id' => $user->id,
            'cart_data' => [
                '1' => [
                    'name' => 'Produto Teste',
                    'price' => 50.00,
                    'quantity' => 2,
                    'image' => 'test.jpg'
                ]
            ],
            'created_at' => now()->subHours(1), // 1 hora atrás
        ]);

        // Executar o comando
        $this->artisan('cart:check-abandoned')
            ->expectsOutput('Checking for abandoned carts...')
            ->assertExitCode(0);

        // Verificar que o email foi enviado
        Mail::assertSent(AbandonedCartMail::class);

        // Verificar que o carrinho foi marcado como email enviado
        $abandonedCart->refresh();
        $this->assertNotNull($abandonedCart->email_sent_at);
    }

    public function test_abandoned_cart_email_not_sent_twice()
    {
        Mail::fake();

        $user = User::factory()->create();

        $abandonedCart = AbandonedCart::create([
            'user_id' => $user->id,
            'cart_data' => [
                '1' => [
                    'name' => 'Produto Teste',
                    'price' => 50.00,
                    'quantity' => 1,
                ]
            ],
            'email_sent_at' => now(), // Email já foi enviado
            'created_at' => now()->subHours(2),
        ]);

        // Executar o comando
        $this->artisan('cart:check-abandoned')
            ->assertExitCode(0);

        // Verificar que NENHUM email foi enviado
        Mail::assertNotSent(AbandonedCartMail::class);
    }

    public function test_recovered_cart_does_not_send_email()
    {
        Mail::fake();

        $user = User::factory()->create();

        $abandonedCart = AbandonedCart::create([
            'user_id' => $user->id,
            'cart_data' => [
                '1' => [
                    'name' => 'Produto Teste',
                    'price' => 50.00,
                    'quantity' => 1,
                ]
            ],
            'recovered_at' => now(), // Carrinho já foi recuperado
            'created_at' => now()->subHours(2),
        ]);

        // Executar o comando
        $this->artisan('cart:check-abandoned')
            ->assertExitCode(0);

        // Verificar que NENHUM email foi enviado
        Mail::assertNotSent(AbandonedCartMail::class);
    }

    public function test_cart_recovery_url_works()
    {
        $user = User::factory()->create();

        $abandonedCart = AbandonedCart::create([
            'user_id' => $user->id,
            'cart_data' => [
                '1' => [
                    'name' => 'Produto Teste',
                    'price' => 50.00,
                    'quantity' => 2,
                ]
            ]
        ]);

        // Testar o URL de recuperação
        $response = $this->actingAs($user)
            ->get(route('cart.recover', ['token' => $abandonedCart->token]));

        $response->assertRedirect(route('cart.index'));

        // Verificar que o carrinho foi marcado como recuperado
        $abandonedCart->refresh();
        $this->assertNotNull($abandonedCart->recovered_at);
    }

    public function test_cart_recovery_with_invalid_token()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('cart.recover', ['token' => 'invalid-token']));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }
}
