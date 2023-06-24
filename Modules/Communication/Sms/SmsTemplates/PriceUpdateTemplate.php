<?php

namespace Modules\Communication\Sms\SmsTemplates;

use DB;
use Modules\Communication\Entities\User;
use Modules\Communication\Interface\SmsServiceInterface;

class PriceUpdateTemplate
{
    public function __construct(
        private SmsServiceInterface $smsService
    ) {
    }

    public function sendPriceUpdateSms()
    {
        $numbersRaw = User::permission('price_update_notification')->pluck('phone')->toArray() ?? [];
        $numbersFiltered = array_filter($numbersRaw, fn ($item) => ! is_null($item) && (strlen($item) >= 10));
        $numbers = array_unique($numbersFiltered);

        \Log::info($numbers);

        $max = DB::table('recent_prices')->max('created_at');
        $recentPrices = DB::table('recent_prices')->leftJoin('products', 'recent_prices.product_id', 'products.id')
            ->where('recent_prices.created_at', $max)
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as subcategories', 'products.parent_category_id', 'subcategories.id')
            ->select(
                'categories.name as cate_name',
                'subcategories.name as sub_cate_name',
                'recent_prices.start_time',
                'recent_prices.end_time',
                'recent_prices.new_price'
            )->distinct()->get();

        $start_time = '';
        $end_time = '';

        $start_time = date('d/m/y h:iA', strtotime($recentPrices->first()->start_time));
        $end_time = date('d/m/y h:iA', strtotime($recentPrices->first()->end_time));

        $prices = '';
        foreach ($recentPrices as $recentPrice) {
            $prices .= $recentPrice->sub_cate_name.':'.floatval($recentPrice->new_price);
            $prices .= "\n";
        }

        $startEndTime = 'From: '.$start_time;
        $startEndTime .= "\n";
        $startEndTime .= 'To: '.$end_time;

        $sms = $startEndTime;
        $sms .= "\n";
        $sms .= $prices;

        $date = date('d M, Y');
        $message = <<< "SMS"
            Price Update ($date)
            {$sms}
            SMS;
        $message = trim($message);

        $sendSmsStatus = false;
        if ($sendSmsStatus) {
            $this->smsService->sendMultiple($message, $numbers);
        }
    }
}
