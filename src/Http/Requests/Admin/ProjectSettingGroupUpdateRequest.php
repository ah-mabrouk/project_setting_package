<?php

namespace Mabrouk\ProjectSetting\Http\Requests\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class ProjectSettingGroupUpdateRequest extends FormRequest
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
            'slug' => 'sometimes|string|min:2|max:191|unique:project_setting_groups,slug,' . $this->project_setting_group->id,

            'name' => 'sometimes|string|min:2|max:191',
            'description' => 'sometimes|string|min:2|max:191',
        ];
    }

    public function getValidatorInstance()
    {
        request()->locale = request()->input('locale');
        return parent::getValidatorInstance();
    }

    public function updateProjectSettingGroup()
    {
        DB::transaction(function () {
            $this->project_setting_group->update([
                'slug' => $this->exists('slug') ? $this->slug : $this->project_setting_group->slug,
            ]);
        });
        return $this->project_setting_group->refresh();
    }
}
