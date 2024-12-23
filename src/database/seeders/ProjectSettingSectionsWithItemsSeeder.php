<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Mabrouk\ProjectSetting\Models\ProjectSetting;
use Mabrouk\ProjectSetting\Models\ProjectSettingType;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;
use Mabrouk\ProjectSetting\Models\ProjectSettingSection;
use Mabrouk\ProjectSetting\Models\ProjectSettingTranslation;
use Mabrouk\ProjectSetting\Models\ProjectSettingSectionTranslation;

class ProjectSettingSectionsWithItemsSeeder extends Seeder
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
        if (!self::purgeCurrentSections()) return;

        $allProjectSettingTypes = ProjectSettingType::all();
        $allProjectSettingGroups = ProjectSettingGroup::all();
        $fillableAttributes = (new ProjectSettingSection())->getFillable();

        for ($i = 0; $i < \count($projectSettingSections); $i++) {
            $sectionGroup = $allProjectSettingGroups->where('slug', $projectSettingSections[$i]['group_slug'])->first();

            if (!$sectionGroup) continue;

            $projectSettingSection = $sectionGroup->projectSettingSections()->create(
                self::filteredFillableProjectSettingSectionObjectData($fillableAttributes, $projectSettingSections[$i])
            );

            self::addModelTranslation($projectSettingSection, $projectSettingSections[$i]['translation_data']);
            self::addSectionSettings($allProjectSettingTypes, $projectSettingSection, $projectSettingSections[$i]['settings']);
        }

        ProjectSetting::cache(true);
    }

    protected static function addSectionSettings(Collection $allProjectSettingTypes, ProjectSettingSection $projectSettingSection, array $projectSettings)
    {
        $fillableAttributes = (new ProjectSetting())->getFillable();

        foreach ($projectSettings as $projectSettingData) {
            $settingType = $allProjectSettingTypes->where('name', $projectSettingData['type_name'])->first();

            if (!$settingType) continue;

            $existingSetting = $projectSettingSection->projectSettings()->where('key', $projectSettingData['key'])->first();

            if ($existingSetting) continue;

            $setting = $projectSettingSection->projectSettings()
                ->create(
                    \array_merge(
                        ['project_setting_type_id' => $settingType->id],
                        self::filteredFillableProjectSettingObjectData($fillableAttributes, $projectSettingData)
                    )
                );
            self::addProjectSettingRelatedObjects($setting, $projectSettingData);
        }
    }

    protected static function addProjectSettingRelatedObjects(ProjectSetting $projectSetting, array $projectSettingData)
    {
        switch (true) {
            case isset($projectSettingData['phone']) && $projectSetting->projectSettingType->name == 'phone':
                $projectSetting->addPhone($projectSettingData['phone']);
                break;
            case isset($projectSettingData['image']) && $projectSetting->projectSettingType->name == 'image':
                $projectSetting->addMedia(type: 'photo', path: $projectSettingData['image'], isMain: true);
                break;
        }
        self::addModelTranslation($projectSetting, $projectSettingData['translation_data']);
    }

    protected static function addModelTranslation(Model $model, array $translations)
    {
        foreach ($translations as $locale => $translationData) {
            $model->translate($translationData, $locale);
        }
    }

    protected static function purgeCurrentSections(): bool
    {
        ProjectSettingSection::truncate();
        ProjectSettingSectionTranslation::truncate();

        return true;
    }

    protected static function filteredFillableProjectSettingSectionObjectData(array $fillables, array $projectSettingSectionData): array
    {
        return \array_filter(
            $projectSettingSectionData,
            function ($attribute) use ($fillables) {
                return \in_array($attribute, $fillables);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    protected static function filteredFillableProjectSettingObjectData(array $fillables, array $projectSettingData): array
    {
        return \array_filter(
            $projectSettingData,
            function ($attribute) use ($fillables) {
                return \in_array($attribute, $fillables);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
