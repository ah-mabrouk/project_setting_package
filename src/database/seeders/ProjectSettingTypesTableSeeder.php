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
                'name' => 'type1',
                'validation_rules' => 'required|string|min:5|max:10',
                'is_translatable' => true,
            ],
            [
                'name' => 'type2',
                'validation_rules' => 'required|string|min:5|max:10',
                'is_translatable' => true,
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
        if (\count($existingTypes) > 0) Artisan::call('setting:types-update', $existingTypes);
    }
}
