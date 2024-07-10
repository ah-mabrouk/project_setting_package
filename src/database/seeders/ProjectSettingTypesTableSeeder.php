<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Mabrouk\ProjectSetting\Models\ProjectSettingType;

class ProjectSettingTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projectSettingTypes = [
            [
                'name' => 'string',
                'validation_rules' => 'sometimes|string|min:2|max:255',
                'is_translatable' => true,
            ],
            [
                'name' => 'text',
                'validation_rules' => 'sometimes|string|min:2|max:40000',
                'is_translatable' => true,
            ],
            [
                'name' => 'textarea',
                'validation_rules' => 'sometimes|string|min:2|max:40000',
                'is_translatable' => true,
            ],
            [
                'name' => 'numeric',
                'validation_rules' => 'sometimes|numeric|min:0|max:999999',
                'is_translatable' => false,
            ],
            [
                'name' => 'boolean',
                'validation_rules' => 'sometimes|boolean',
                'is_translatable' => false,
            ],
            [
                'name' => 'link',
                'validation_rules' => 'sometimes|string|active_url|max:255',
                'is_translatable' => false,
            ],
            [
                'name' => 'phone',
                'validation_rules' => json_encode([
                    'phone' => 'sometimes|array',
                    'phone.country_code' => 'required_with:phone|string|exists:countries,phone_code',
                    'phone.number' => 'required_with:phone|numeric|digits_between:9,15',
                ]),
                'is_translatable' => false,
            ],
            [
                'name' => 'image',
                'validation_rules' => json_encode([
                    'image' => 'sometimes|mimes:png,jpeg|max:512',
                ]),
                'is_translatable' => false,
            ],
        ];

        $currentTypesInTable = ProjectSettingType::pluck('name')->flatten()->toArray();

        $existingTypes = [];

        for ($i = 0; $i < \count($projectSettingTypes); $i++) {
            if (\in_array($projectSettingTypes[$i]['name'], $currentTypesInTable)) {
                $existingTypes[] = $projectSettingTypes[$i];
                continue;
            }
            ProjectSettingType::create($projectSettingTypes[$i]);
        }

        $this->updateExistingProjectSettingTypes($existingTypes);
    }

    protected function updateExistingProjectSettingTypes(array $existingTypes = [])
    {
        if (\count($existingTypes) > 0) Artisan::call('setting:types-update', ['--existing_types' => $existingTypes]);
    }
}
