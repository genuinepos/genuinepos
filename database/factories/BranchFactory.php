<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Branch>
 */
final class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'branch_code' => $this->faker->word,
            'phone' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'zip_code' => $this->faker->word,
            'alternate_phone_number' => $this->faker->word,
            'country' => $this->faker->country,
            'email' => $this->faker->safeEmail,
            'website' => $this->faker->word,
            'logo' => $this->faker->word,
            'invoice_schema_id' => \App\Models\InvoiceSchema::factory(),
            'add_sale_invoice_layout_id' => \App\Models\InvoiceLayout::factory(),
            'pos_sale_invoice_layout_id' => \App\Models\InvoiceLayout::factory(),
            'default_account_id' => $this->faker->randomNumber(),
            'purchase_permission' => $this->faker->boolean,
            'after_purchase_store' => $this->faker->boolean,
        ];
    }
}
