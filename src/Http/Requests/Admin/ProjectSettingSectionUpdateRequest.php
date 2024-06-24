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
            'name' => 'sometimes|string|min:2|max:191',
            'description' => 'sometimes|string|min:2|max:191',
        ];
    }

    public function storeProjectSettingSection()
    {
        DB::transaction(function () {
            $this->project_setting_section->update();
        });
        return $this->project_setting_section->refresh();
    }
}
