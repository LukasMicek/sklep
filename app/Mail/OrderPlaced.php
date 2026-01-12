<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->load('items');
    }

    public function build()
    {
        return $this->subject('Potwierdzenie zamówienia #' . $this->order->id)
            ->view('emails.order_placed');
    }
}

