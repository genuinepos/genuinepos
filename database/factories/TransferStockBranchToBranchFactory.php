<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TransferStockBranchToBranch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TransferStockBranchToBranch>
 */
final class TransferStockBranchToBranchFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = TransferStockBranchToBranch::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'ref_id' => $this->faker->word,
            'sender_branch_id' => \App\Models\Branch::factory(),
            'sender_warehouse_id' => \App\Models\Warehouse::factory(),
            'receiver_branch_id' => \App\Models\Branch::factory(),
            'receiver_warehouse_id' => \App\Models\Warehouse::factory(),
            'total_item' => $this->faker->randomFloat(),
            'total_stock_value' => $this->faker->randomFloat(),
            'expense_account_id' => $this->faker->randomNumber(),
            'bank_account_id' => $this->faker->randomNumber(),
            'payment_method_id' => $this->faker->randomNumber(),
            'payment_note' => $this->faker->word,
            'transfer_cost' => $this->faker->randomFloat(),
            'total_send_qty' => $this->faker->randomFloat(),
            'total_received_qty' => $this->faker->randomFloat(),
            'total_pending_qty' => $this->faker->randomFloat(),
            'receive_status' => $this->faker->boolean,
            'date' => $this->faker->word,
            'transfer_note' => $this->faker->text,
            'receiver_note' => $this->faker->text,
            'report_date' => $this->faker->word,
        ];
    }
}
