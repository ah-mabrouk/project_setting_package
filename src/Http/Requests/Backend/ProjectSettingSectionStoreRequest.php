<?php

namespace Mabrouk\ProjectSetting\Http\Requests\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Mabrouk\ProjectSetting\Models\ProjectSettingSection;

class ProjectSettingSectionStoreRequest extends FormRequest
{
    public ProjectSettingSection $projectSettingSection;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'key' => 'sometimes|string|unique:project_setting_sections,key',
            'name' => 'required|string|min:2|max:191',
            'description' => 'required|string|min:2|max:191',
        ];
    }

    public function storeProjectSettingSection(): ProjectSettingSection
    {
        DB::transaction(function () {
            $this->projectSettingSection = $this->project_setting_group->projectSettingSections()->create([
                'key' => $this->exists('key') ? $this->key : $this->generateUniqueKey(),
            ]);
        });

        return $this->projectSettingSection->refresh();
    }

    private function generateUniqueKey(): string
    {
        $key = str()->slug($this->name) . rand(100, 999);

        if (ProjectSettingSection::where('key', $key)->exists()) {
            return $this->generateUniqueKey();
        }

        return $key;
    }

    public function attributes(): array
    {
        return [
            'key' => __('mabrouk/project_settings/project_setting_sections.attributes.key'),
            'name' => __('mabrouk/project_settings/project_setting_sections.attributes.name'),
            'description' => __('mabrouk/project_settings/project_setting_sections.attributes.description'),
        ];
    }
}
