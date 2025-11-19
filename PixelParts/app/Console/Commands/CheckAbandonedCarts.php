<?php

namespace App\Console\Commands;

use App\Mail\AbandonedCartMail;
use App\Models\AbandonedCart;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CheckAbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:check-abandoned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for abandoned carts and send recovery emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for abandoned carts...');

        $emailsSent = 0;

        // Find abandoned carts that haven't been emailed yet and are older than 24 hours (0 for testing)
        $abandonedCarts = AbandonedCart::whereNull('email_sent_at')
            ->whereNull('recovered_at')
            ->where('created_at', '<', now()->subHours(0)) // Change to 24 for production
            ->with('user')
            ->get();

        foreach ($abandonedCarts as $abandonedCart) {
            $user = $abandonedCart->user;

            if (!$user) {
                continue;
            }

            // Prepare cart items for email
            $cartItems = [];
            foreach ($abandonedCart->cart_data as $productId => $item) {
                $cartItems[] = [
                    'name' => $item['name'] ?? 'Produto',
                    'image' => $item['image'] ?? null,
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 1,
                ];
            }

            // Skip if cart is empty
            if (empty($cartItems)) {
                continue;
            }

            // Calculate total
            $totalAmount = array_reduce($cartItems, function ($total, $item) {
                return $total + ($item['price'] * $item['quantity']);
            }, 0);

            // Generate recovery URL
            $recoveryUrl = route('cart.recover', ['token' => $abandonedCart->token]);

            // Send email
            try {
                Mail::to($user->email)->send(new AbandonedCartMail($abandonedCart));

                // Mark as email sent
                $abandonedCart->markAsEmailSent();

                $emailsSent++;
                $this->info("Sent recovery email to: {$user->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Finished! Sent {$emailsSent} recovery emails.");

        return Command::SUCCESS;
    }
}
