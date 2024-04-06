<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSubscriptionAddShopInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private $user,
        private $increasedShopCount,
        private $pricePerShop,
        private $pricePeriod,
        private $pricePeriodCount,
        private $subtotal,
        private $netTotalAmount,
        private $discount,
        private $totalPayable,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Increase Shop Invoice',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build(): self
    {
        $user = $this->user;
        $increasedShopCount = $this->increasedShopCount;
        $pricePerShop = $this->pricePerShop;
        $pricePeriod = $this->pricePeriod;
        $pricePeriodCount = $this->pricePeriodCount;
        $subtotal = $this->subtotal;
        $netTotalAmount = $this->netTotalAmount;
        $discount = $this->discount;
        $totalPayable = $this->totalPayable;

        return $this->view(
            'mail.invoice.billing_add_shop_invoice',
            compact(
                'user',
                'increasedShopCount',
                'pricePerShop',
                'pricePeriod',
                'pricePeriodCount',
                'subtotal',
                'netTotalAmount',
                'discount',
                'totalPayable'
            )
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
