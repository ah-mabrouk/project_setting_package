<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Backend;

use App\Http\Resources\Admin\PhoneResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Mabrouk\Mediable\Http\Resources\MediaResource;

class ProjectSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,

            'validation_rule' => \in_array($this->projectSettingType->name, ['phone', 'image']) ? (array) \json_decode($this->validationRule) : $this->validationRule,

            'name' => $this->name,
            'description' => $this->description,
            'value' => $this->when(! \in_array($this->projectSettingType->name, ['phone', 'image']), $this->value),

            'translatable' => $this->isTranslatable,
            'admin_has_display_control' => $this->is_admin_has_display_control,
            'displayed' => $this->is_displayed,
            
            'phone' => $this->when($this->projectSettingType->name == 'phone', new PhoneResource($this->phone)),
            'image' => $this->when($this->projectSettingType->name == 'image', new MediaResource($this->mainImage)),
            'type' => new ProjectSettingTypeResource($this->projectSettingType),
            'section' => new ProjectSettingSectionSimpleResource($this->projectSettingSection),
        ];
    }
}
