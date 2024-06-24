<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSettingTypeResource extends JsonResource
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

            'name' => $this->name,
            'validation_rules' => $this->validation_rules,

            'translatable' => $this->is_translatable,
        ];
    }
}
