<?php

namespace Mabrouk\ProjectSetting\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Mabrouk\ProjectSetting\Models\ProjectSetting;
use Mabrouk\ProjectSetting\Models\ProjectSettingType;

class ProjectSettingStoreRequest extends FormRequest
{
    public $projectSettingType;

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $this->projectSettingType = ProjectSettingType::find($this->type);

        return \array_merge([
            'section' => 'required|integer|exists:project_setting_sections,id',
            'group' => 'required|integer|exists:project_setting_groups,id',
            'type' => 'required|integer|exists:project_setting_types,id',
            'key' => 'required|string|unique:project_settings,key',
            'non_translatable_value' => 'sometimes|string|min:2|max:10000',

            'name' => 'required|string|min:2|max:191',
            'description' => 'required|string|min:2|max:191',

            'custom_validation_rules' => 'sometimes|string|min:2|max:191',

            'visible' => 'required|boolean',
            'editable' => 'required|boolean',
            'return_to_client' => 'required|boolean',
            'displayed' => 'required|boolean',
            'admin_has_display_control' => 'required|boolean',
        ], $this->valueValidationRules());
    }

    public function getValidatorInstance()
    {
        $this->merge(format_json_strings_to_boolean(['editable', 'return_to_client', 'displayed', 'admin_has_display_control']));

        if ($this->exists('value')) request()->merge(['key_value' => $this->value]);
        if ($this->exists('phone')) $this->merge(format_json_strings_to_array(['phone']));

        return parent::getValidatorInstance();
    }

    public function storeProjectSetting(): ProjectSetting
    {
        $projectSetting = ProjectSetting::create([
            'project_setting_section_id' => $this->section,
            'project_setting_type_id' => $this->type,
            'key' => $this->key,
            'non_translatable_value' => $this->exists('value') && (! $this->projectSettingType->is_translatable) ? $this->value : null,
            'custom_validation_rules' => $this->custom_validation_rules,
            'is_visible' => $this->visible,
            'is_editable' => $this->editable,
            'is_return_to_client' => $this->return_to_client,
            'is_admin_has_display_control' => $this->admin_has_display_control,
            'is_displayed' => $this->displayed,
        ]);

        if ($this->exists('phone')) {
            $projectSetting->addPhone(\array_merge($this->phone, ['type' => Str::slug($this->project_setting->name, '_')]));
        }

        if ($this->exists('image')) {
            $projectSetting->addMedia(
                'photo',
                $this->image->storeAs(
                    $projectSetting->photosDirectory,
                    Str::slug($projectSetting->name . random_by(), '_') . '.' . $this->image->getClientOriginalExtension()
                ),
                'image',
                null,
                true
            );
        }

        ProjectSetting::cache(true);

        return $projectSetting;
    }

    protected function valueValidationRules(): array
    {
        if (\in_array($this->projectSettingType->name, ['phone', 'image'])) {
            return (array) \json_decode($this->project_setting->validationRule);
        }

        $validationRules = $this->custom_validation_rules ?? $this->projectSettingType->validation_rules;

        return ['value' => 'required|' . $validationRules];
    }

    public function attributes(): array
    {
        return [
            'section' => __('mabrouk/project_settings/project_settings.attributes.section'),
            'name' => __('mabrouk/project_settings/project_settings.attributes.name'),
            'description' => __('mabrouk/project_settings/project_settings.attributes.description'),
            'custom_validation_rules' => __('mabrouk/project_settings/project_settings.attributes.custom_validation_rules'),
            'editable' => __('mabrouk/project_settings/project_settings.attributes.editable'),
            'return_to_client' => __('mabrouk/project_settings/project_settings.attributes.return_to_client'),
            'phone' => __('mabrouk/project_settings/project_settings.attributes.phone.phone'),
            'phone.number' => __('mabrouk/project_settings/project_settings.attributes.phone.number'),
            'phone.country_code' => __('mabrouk/project_settings/project_settings.attributes.phone.country_code'),
            'image' => __('mabrouk/project_settings/project_settings.attributes.image'),
            'value' => __('mabrouk/project_settings/project_settings.attributes.value'),
            'visible' => __('mabrouk/project_settings/project_settings.attributes.visible'),
            'displayed' => __('mabrouk/project_settings/project_settings.attributes.displayed'),
            'admin_has_display_control' => __('mabrouk/project_settings/project_settings.attributes.admin_has_display_control'),
        ];
    }
}
