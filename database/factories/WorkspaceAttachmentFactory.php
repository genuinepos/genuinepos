<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Essential\WorkspaceAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Essential\WorkspaceAttachment>
 */
final class WorkspaceAttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkspaceAttachment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'workspace_id' => $this->faker->randomNumber(),
            'attachment' => $this->faker->word,
            'extension' => $this->faker->word,
        ];
    }
}
