<?php

namespace Mabrouk\ProjectSetting\database\factories;

use Mabrouk\ProjectSetting\Models\ProjectSetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Mabrouk\ProjectSetting\Models\ProjectSettingType;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ProjectSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'project_setting_section_id' => ProjectSettingGroup::factory(),
            'project_setting_type_id' => ProjectSettingType::factory(),

            'key' => $this->faker->text(),
            'non_translatable_value' => $this->faker->text(),
            'custom_validation_rules' => $this->faker->text(),

            'is_visible' => 1,
            'is_editable' => 1,
            'is_return_to_client' => 1,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($projectSetting) {

            $projectSetting->translate([
                'name' => $this->faker->words(2, true),
                'description' => $this->faker->text(),
                'key_value' => $projectSetting->non_translatable_value != null ? $this->faker->text() : null,
            ], 'en');

            $projectSetting->translate([
                'name' => $this->faker->words(2, true),
                'description' => $this->faker->text(),
                'key_value' => $projectSetting->non_translatable_value != null ? $this->faker->text() : null,
            ], 'ar');
        });
    }
}
