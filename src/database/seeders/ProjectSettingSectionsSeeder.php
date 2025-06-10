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

        $fillableAttributes = (new ProjectSettingSection())->getFillable();

        for ($i = 0; $i < \count($projectSettingSections); $i++) {
            
            $projectGroupData = $projectSettingSections[$i];
            $sectionGroup = $allProjectSettingGroups->where('slug', $projectGroupData['group_slug'])->first();

            if ($sectionGroup) {

                for ($j = 0; $j < \count($projectGroupData['sections']); $j++):
                    $projectSectionData = $projectGroupData['sections'][$j];
                    if (!\in_array($projectSectionData['key'], $currentProjectSettingSectionsInTable)):
                        $projectSettingSection = $sectionGroup->projectSettingSections()->create(
                            filteredFillableModelObjectData(actualModelFillable: $fillableAttributes, receivedData: $projectSectionData)
                        );
                        addModelTranslation(model: $projectSettingSection, translations: $projectSectionData['translation_data']);
                    endif;
                endfor;
            }
        }
    }
}
