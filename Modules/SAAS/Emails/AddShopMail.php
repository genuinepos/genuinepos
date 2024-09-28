<?php

namespace Modules\SAAS\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddShopMail extends Mailable
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
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Increased Store Invoice',
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
            'saas::mail.add_shop_invoice',
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
