<?php

namespace Mabrouk\ProjectSetting\Http\Requests\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class ProjectSettingSectionUpdateRequest extends FormRequest
{
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
            'project_setting_group' => 'sometimes|integer|exists:project_setting_groups,id',
            'name' => 'sometimes|string|min:2|max:191',
            'description' => 'sometimes|string|min:2|max:191',
        ];
    }

    public function updateProjectSettingSection()
    {
        DB::transaction(function () {
            $this->project_setting_section->update([
                'project_setting_group_id' => $this->exists('project_setting_group') ? $this->project_setting_group : $this->project_setting_section->project_setting_group_id,
            ]);
        });
        return $this->project_setting_section->refresh();
    }

    public function attributes(): array
    {
        return [
            'name' => __('mabrouk/project_settings/project_setting_sections.attributes.name'),
            'description' => __('mabrouk/project_settings/project_setting_sections.attributes.description'),
        ];
    }
}
