<?php

namespace Database\Seeders;

use Illuminate\Console\Command;
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
        if (! self::purgeCurrentSections()) return;

        $allProjectSettingTypes = ProjectSettingType::all();
        $allProjectSettingGroups = ProjectSettingGroup::all();

        for ($i = 0; $i < \count($projectSettingSections); $i++) {
            $sectionGroup = $allProjectSettingGroups->where('slug', $projectSettingSections[$i]['group_slug'])->first();

            if (! $sectionGroup) continue;

            $projectSettingSection = $sectionGroup->projectSettingSections()->create($projectSettingSections[$i]);
            self::addModelTranslation($projectSettingSection, $projectSettingSections[$i]['translations']);
            self::addSectionSettings($allProjectSettingTypes, $projectSettingSection, $projectSettingSections[$i]['settings']);
        }
    }

    protected static function addSectionSettings(Collection $allProjectSettingTypes, ProjectSettingSection $projectSettingSection, array $settings)
    {
        foreach ($settings as $settingsData) {
            $settingType = $allProjectSettingTypes->where('name', $settingsData['type_name'])->first();

            if (! $settingType) continue;

            $setting = $projectSettingSection->projectSettings()
                ->create(\array_merge(['project_setting_type_id' => $settingType->id], $settingsData));
            self::addModelTranslation($setting, $settingsData['translations']);
        }
    }

    protected static function addModelTranslation(Model $projectSettingSection, array $translations)
    {
        foreach ($translations as $locale => $translationData) {
            $projectSettingSection->translate($translationData, $locale);
        }
    }

    protected function purgeCurrentSections(): bool
    {
        if (ProjectSettingSection::count() == 0) return true;

        $confirmPurgeCurrentData = (new Command())->confirm('this action will purge the current sections and settings keys and fill it with the seeder data. do you wish to continue?', false);

        if (! $confirmPurgeCurrentData) return false;

        ProjectSettingSection::truncate();
        ProjectSettingSectionTranslation::truncate();
        ProjectSetting::truncate();
        ProjectSettingTranslation::truncate();

        return true;
    }
}
