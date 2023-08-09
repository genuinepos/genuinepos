<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\InvoiceLayout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\InvoiceLayout>
 */
final class InvoiceLayoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceLayout::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'layout_design' => $this->faker->boolean,
            'show_shop_logo' => $this->faker->boolean,
            'header_text' => $this->faker->text,
            'is_header_less' => $this->faker->boolean,
            'gap_from_top' => $this->faker->randomNumber(),
            'show_seller_info' => $this->faker->boolean,
            'customer_name' => $this->faker->boolean,
            'customer_tax_no' => $this->faker->boolean,
            'customer_address' => $this->faker->boolean,
            'customer_phone' => $this->faker->boolean,
            'sub_heading_1' => $this->faker->word,
            'sub_heading_2' => $this->faker->word,
            'sub_heading_3' => $this->faker->word,
            'invoice_heading' => $this->faker->word,
            'quotation_heading' => $this->faker->word,
            'draft_heading' => $this->faker->word,
            'challan_heading' => $this->faker->word,
            'branch_landmark' => $this->faker->boolean,
            'branch_city' => $this->faker->boolean,
            'branch_state' => $this->faker->boolean,
            'branch_country' => $this->faker->boolean,
            'branch_zipcode' => $this->faker->boolean,
            'branch_phone' => $this->faker->boolean,
            'branch_alternate_number' => $this->faker->boolean,
            'branch_email' => $this->faker->boolean,
            'product_img' => $this->faker->boolean,
            'product_cate' => $this->faker->boolean,
            'product_brand' => $this->faker->boolean,
            'product_imei' => $this->faker->boolean,
            'product_w_type' => $this->faker->boolean,
            'product_w_duration' => $this->faker->boolean,
            'product_w_discription' => $this->faker->boolean,
            'product_discount' => $this->faker->boolean,
            'product_tax' => $this->faker->boolean,
            'product_price_inc_tax' => $this->faker->boolean,
            'product_price_exc_tax' => $this->faker->boolean,
            'invoice_notice' => $this->faker->text,
            'sale_note' => $this->faker->boolean,
            'show_total_in_word' => $this->faker->boolean,
            'footer_text' => $this->faker->text,
            'bank_name' => $this->faker->word,
            'bank_branch' => $this->faker->word,
            'account_name' => $this->faker->word,
            'account_no' => $this->faker->word,
            'is_default' => $this->faker->boolean,
        ];
    }
}
