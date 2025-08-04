<?php

namespace Mabrouk\ProjectSetting\Http\Requests\Backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;

class ProjectSettingGroupStoreRequest extends FormRequest
{
    public ProjectSettingGroup $projectSettingGroup;

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
    public function rules()
    {
        return [
            'slug' => 'required|string|min:2|max:191|unique:project_setting_groups,slug',

            'name' => 'required|string|min:2|max:191',
            'description' => 'required|string|min:2|max:191',
        ];
    }

    public function storeProjectSettingGroup()
    {
        DB::transaction(function () {
            $this->projectSettingGroup = ProjectSettingGroup::create([
                'slug' => $this->slug,
            ]);
        });
        return $this->projectSettingGroup->refresh();
    }

    public function attributes(): array
    {
        return [
            'slug' => __('mabrouk/project_settings/project_setting_groups.attributes.slug'),
            'name' => __('mabrouk/project_settings/project_setting_groups.attributes.name'),
            'description' => __('mabrouk/project_settings/project_setting_groups.attributes.description'),
        ];
    }
}
