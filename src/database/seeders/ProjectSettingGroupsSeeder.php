<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;

class ProjectSettingGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }

    public static function seedGroups(array $projectSettingGroups)
    {
        $currentProjectSettingGroupsInTable = ProjectSettingGroup::pluck('slug')->flatten()->toArray();

        $fillableAttributes = (new ProjectSettingGroup())->getFillable();

        for ($i = 0; $i < \count($projectSettingGroups); $i++) {

            $projectSettingGroup = $projectSettingGroups[$i];
            
            if (! \in_array($projectSettingGroup['slug'], $currentProjectSettingGroupsInTable)) {
                $projectSettingGroup = ProjectSettingGroup::create(
                    filteredFillableModelObjectData(actualModelFillable: $fillableAttributes, receivedData: $projectSettingGroup)
                );
                addModelTranslation(model: $projectSettingGroup, translations: $projectSettingGroup['translation_data']);
            }
        }
    }
}
