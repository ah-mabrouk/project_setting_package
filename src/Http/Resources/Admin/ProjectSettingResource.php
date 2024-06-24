<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

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

            'validation_rule' => $this->ValidationRule,

            'name' => $this->name,
            'description' => $this->description,
            'value' => $this->actualValue,

            'translatable' => $this->isTranslatable,

            'type' => new ProjectSettingTypeResource($this->projectSettingType),
        ];
    }
}
