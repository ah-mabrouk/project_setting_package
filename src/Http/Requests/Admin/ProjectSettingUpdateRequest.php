<?php

namespace Mabrouk\ProjectSetting\Http\Requests\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Mabrouk\ProjectSetting\Models\ProjectSetting;

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
        return \array_merge([
            // 'section' => 'sometimes|integer|exists:project_setting_sections,id',

            // 'name' => 'sometimes|string|min:2|max:191',
            // 'description' => 'sometimes|string|min:2|max:191',

            'displayed' => [
                'sometimes', 
                'boolean',
                function ($attribute, $value, $fail) {
                    if (!$this->project_setting->is_admin_has_display_control) {
                        $fail(__('mabrouk/project_settings/project_settings.errors.displayed_not_allowed'));
                    }
                },
            ],

        ], $this->valueValidationRules());
    }

    public function getValidatorInstance()
    {
        request()->locale = request()->input('locale');
        $this->merge(format_json_strings_to_boolean(['displayed']));

        if ($this->exists('value') && $this->project_setting->isTranslatable) request()->merge(['key_value' => $this->value]);
        if ($this->exists('phone')) $this->merge(format_json_strings_to_array(['phone']));

        return parent::getValidatorInstance();
    }

    public function updateProjectSetting()
    {
        DB::transaction(function () {
            $this->project_setting->update([
                // 'project_setting_section_id' => $this->exists('section') ? $this->section : $this->project_setting->project_setting_section_id,
                'non_translatable_value' => $this->exists('value') && (! $this->project_setting->isTranslatable) ? $this->value : $this->project_setting->non_translatable_value,

                'is_displayed' => $this->exists('displayed') ? $this->displayed : $this->project_setting->is_displayed,
            ]);
            $this->updatePhone()
                ->updateImage();
        });

        ProjectSetting::cache(true);
        
        return $this->project_setting->refresh();
    }

    protected function valueValidationRules(): array
    {
        if (\in_array($this->project_setting->projectSettingType->name, ['phone', 'image'])) return (array) \json_decode($this->project_setting->validationRule);

        return ['value' => $this->project_setting->validationRule];
    }

    protected function updatePhone()
    {
        if (! $this->exists('phone')) return $this;

        $previousPhoneNumber = $this->project_setting->phone;
        if ($previousPhoneNumber != null) {
            $this->project_setting->editPhone($previousPhoneNumber, \array_merge($this->phone, ['type' => Str::slug($this->project_setting->name, '_')]));
            return $this;
        }
        $this->project_setting->addPhone(\array_merge($this->phone, ['type' => Str::slug($this->project_setting->name, '_')]));

        return $this;
    }

    protected function updateImage()
    {
        if (! $this->exists('image')) return $this;

        if ($this->project_setting->mainImage != null) {
            $this->project_setting->editMedia(
                $this->project_setting->mainImage,
                $this->image->storeAs(
                    $this->project_setting->photosDirectory,
                    Str::slug($this->project_setting->name . random_by(), '_') . '.' . $this->image->getClientOriginalExtension()
                ),
                'image',
                null,
                true,
            );
            return $this;
        }

        $this->project_setting->addMedia(
            'photo',
            $this->image->storeAs(
                $this->project_setting->photosDirectory,
                Str::slug($this->project_setting->name . random_by(), '_') . '.' . $this->image->getClientOriginalExtension()
            ),
            'image',
            null,
            true
        );

        return $this;
    }

    public function attributes(): array
    {
        return [
            'section' => __('mabrouk/project_settings/project_settings.attributes.section'),
            'name' => __('mabrouk/project_settings/project_settings.attributes.name'),
            'description' => __('mabrouk/project_settings/project_settings.attributes.description'),
            'phone' => __('mabrouk/project_settings/project_settings.attributes.phone.phone'),
            'phone.number' => __('mabrouk/project_settings/project_settings.attributes.phone.number'),
            'phone.country_code' => __('mabrouk/project_settings/project_settings.attributes.phone.country_code'),
            'image' => __('mabrouk/project_settings/project_settings.attributes.image'),
            'value' => __('mabrouk/project_settings/project_settings.attributes.value'),
            'displayed' => __('mabrouk/project_settings/project_settings.attributes.displayed'),
        ];
    }
}
