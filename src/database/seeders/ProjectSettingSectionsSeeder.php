<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;
use Mabrouk\ProjectSetting\Models\ProjectSettingSection;

class ProjectSettingSectionsSeeder extends Seeder
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

    public static function seedSections(array $projectSettingSections)
    {
        $allProjectSettingGroups = ProjectSettingGroup::all();

        $currentProjectSettingSectionsInTable = ProjectSettingSection::pluck('key')->flatten()->toArray();

        $fillableAttributes = (new ProjectSettingGroup())->getFillable();

        for ($i = 0; $i < \count($projectSettingSections); $i++) {
            
            $projectSectionData = $projectSettingSections[$i];
            $sectionGroup = $allProjectSettingGroups->where('slug', $projectSectionData['group_slug'])->first();

            if ($sectionGroup && !\in_array($projectSectionData['key'], $currentProjectSettingSectionsInTable)) {

                $projectSettingSection = $sectionGroup->projectSettingSections()->create(
                    filteredFillableModelObjectData(actualModelFillable: $fillableAttributes, receivedData: $projectSectionData)
                );
    
                addModelTranslation(model: $projectSettingSection, translations: $projectSectionData['translation_data']);
            }
        }
    }
}
