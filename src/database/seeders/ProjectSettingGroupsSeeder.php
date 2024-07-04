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

        for ($i = 0; $i < \count($projectSettingGroups); $i++) {
            if (! \in_array($projectSettingGroups[$i]['slug'], $currentProjectSettingGroupsInTable)) {
                $projectSettingGroup = ProjectSettingGroup::create($projectSettingGroups[$i]);
                self::addGroupTranslation($projectSettingGroup, $projectSettingGroups[$i]['translation_data']);
            }
        }
    }

    protected static function addGroupTranslation(ProjectSettingGroup $projectSettingGroup, array $translations)
    {
        foreach ($translations as $locale => $translationData) {
            $projectSettingGroup->translate($translationData, $locale);
        }
    }
}
