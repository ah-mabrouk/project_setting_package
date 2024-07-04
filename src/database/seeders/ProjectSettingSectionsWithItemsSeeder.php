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

            self::addProjectSettingTranslation($projectSettingSection, $projectSettingSections[$i]['translation_data']);
            self::addSectionSettings($allProjectSettingTypes, $projectSettingSection, $projectSettingSections[$i]['settings']);
        }
    }

    protected static function addSectionSettings(Collection $allProjectSettingTypes, ProjectSettingSection $projectSettingSection, array $projectSettings)
    {
        $fillableAttributes = (new ProjectSetting())->getFillable();

        foreach ($projectSettings as $projectSettingData) {
            $settingType = $allProjectSettingTypes->where('name', $projectSettingData['type_name'])->first();

            if (!$settingType) continue;

            $setting = $projectSettingSection->projectSettings()
                ->create(
                    \array_merge(
                        ['project_setting_type_id' => $settingType->id],
                        self::filteredFillableProjectSettingObjectData($fillableAttributes, $projectSettingData)
                    )
                );
            self::addProjectSettingTranslation($setting, $projectSettingData['translation_data']);
        }
    }

    protected static function addProjectSettingTranslation(Model $projectSettingSection, array $translations)
    {
        foreach ($translations as $locale => $translationData) {
            $projectSettingSection->translate($translationData, $locale);
        }
    }

    protected static function purgeCurrentSections(): bool
    {
        ProjectSettingSection::truncate();
        ProjectSettingSectionTranslation::truncate();
        ProjectSetting::truncate();
        ProjectSettingTranslation::truncate();

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
