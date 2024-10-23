<?php

namespace Mabrouk\ProjectSetting\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mabrouk\ProjectSetting\Models\ProjectSettingType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ProjectSettingTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectSettingType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'validation_rules' => $this->faker->randomElement(['required|string|max:255', 'nullable|integer', 'required|email']),
            'is_translatable' => $this->faker->boolean,
        ];
    }
}
