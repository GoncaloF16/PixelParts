<?php

namespace App\Mail;

use App\Models\AbandonedCart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbandonedCartMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AbandonedCart $abandonedCart
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Carrinho Pendente - PixelParts',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-cart',
            with: [
                'user' => $this->abandonedCart->user,
                'cartItems' => $this->abandonedCart->cart_data,
                'totalAmount' => $this->abandonedCart->getTotalAmount(),
                'recoveryUrl' => route('cart.recover', ['token' => $this->abandonedCart->token]),
            ]
        );
    }
}
