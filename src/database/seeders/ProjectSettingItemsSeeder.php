<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Mabrouk\ProjectSetting\Models\ProjectSetting;
use Mabrouk\ProjectSetting\Models\ProjectSettingType;
use Mabrouk\ProjectSetting\Models\ProjectSettingSection;

class ProjectSettingItemsSeeder extends Seeder
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

    public static function seedSections(array $projectSettingItems)
    {
        $allProjectSettingTypes = ProjectSettingType::all();
        $allProjectSettingSections = ProjectSettingSection::all()->pluck('key');
        $currentProjectSettingKeys = ProjectSetting::all()->pluck('key');

        for ($i = 0; $i < \count($projectSettingItems); $i++) {

            $sectionData = $projectSettingItems[$i];

            $projectSettingSection = $allProjectSettingSections->where('key', $sectionData['section_key'])->first();

            if ($projectSettingSection) {
                $settingItemsToBeAdded = collect($sectionData[$i]['settings'])->whereNotIn('key', $currentProjectSettingKeys)->toArray();
    
                self::addSectionSettings($allProjectSettingTypes, $projectSettingSection, $settingItemsToBeAdded);
            }
        }

        ProjectSetting::cache(true);
    }

    protected static function addSectionSettings(Collection $allProjectSettingTypes, ProjectSettingSection $projectSettingSection, array $projectSettings): void
    {
        $fillableAttributes = (new ProjectSetting())->getFillable();

        foreach ($projectSettings as $projectSettingData) {
            $settingType = $allProjectSettingTypes->where('name', $projectSettingData['type_name'])->first();

            if (!$settingType) {

                $settingItemData = \array_merge(
                    ['project_setting_type_id' => $settingType->id],
                    filteredFillableModelObjectData(actualModelFillable: $fillableAttributes, receivedData: $projectSettingData)
                );

                $setting = $projectSettingSection->projectSettings()->create($settingItemData);
                self::addProjectSettingRelatedObjects($setting, $projectSettingData);
            }
        }
    }

    protected static function addProjectSettingRelatedObjects(ProjectSetting $projectSetting, array $projectSettingData): void
    {
        switch (true) {
            case isset($projectSettingData['phone']) && $projectSetting->projectSettingType->name == 'phone':
                $projectSetting->addPhone($projectSettingData['phone']);
                break;
            case isset($projectSettingData['image']) && $projectSetting->projectSettingType->name == 'image':
                $projectSetting->addMedia(type: 'photo', path: $projectSettingData['image'], isMain: true);
                break;
        }
        addModelTranslation(model: $projectSetting, translations: $projectSettingData['translation_data']);
    }
}
