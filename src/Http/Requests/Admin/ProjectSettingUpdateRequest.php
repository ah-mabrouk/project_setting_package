<?php

namespace Mabrouk\ProjectSetting\Http\Requests\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class ProjectSettingUpdateRequest extends FormRequest
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
            'section' => 'sometimes|integer|exists:project_setting_sections,id',

            'name' => 'sometimes|string|min:2|max:191',
            'description' => 'sometimes|string|min:2|max:191',
            'value' => $this->project_setting->validationRule,

            'custom_validation_rules' => 'sometimes|string|min:2|max:191',

            'editable' => 'sometimes|boolean',
            'return_to_client' => 'sometimes|boolean',
        ];
    }

    public function getValidatorInstance()
    {
        request()->locale = request()->input('locale');
        $this->merge(format_json_strings_to_boolean(['editable', 'return_to_client']));

        if ($this->exists('value') && $this->project_setting->is_translatable) $this->merge(['key_value' => $this->value]);

        return parent::getValidatorInstance();
    }

    public function updateProjectSetting()
    {
        DB::transaction(function () {
            $this->project_setting->update([
                'project_setting_section_id' => $this->exists('project_setting_section') ? $this->project_setting_section : $this->project_setting->project_setting_section_id,
                'non_translatable_value' => $this->exists('value') && (! $this->project_setting_section->is_translatable) ? $this->value : $this->project_setting->non_translatable_value,
                'custom_validation_rules' => $this->exists('custom_validation_rules') ? $this->custom_validation_rules : $this->project_setting->custom_validation_rules,
                'is_editable' => $this->exists('editable') ? $this->is_editable : $this->project_setting->is_editable,
                'is_return_to_client' => $this->exists('return_to_client') ? $this->is_return_to_client : $this->project_setting->is_return_to_client,
            ]);
        });
        return $this->project_setting->refresh();
    }
}
