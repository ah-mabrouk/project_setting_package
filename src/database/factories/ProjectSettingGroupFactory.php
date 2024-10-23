<?php

namespace Mabrouk\ProjectSetting\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ProjectSettingGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectSettingGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->words(2, true),
            'is_visible' => $this->faker->boolean(70),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($projectSetting) {

            $projectSetting->translate([
                'name' => $this->faker->words(2, true),
                'description' => $this->faker->text(),
            ], 'en');

            $projectSetting->translate([
                'name' => $this->faker->words(2, true),
                'description' => $this->faker->text(),
            ], 'ar');
        });
    }
}
