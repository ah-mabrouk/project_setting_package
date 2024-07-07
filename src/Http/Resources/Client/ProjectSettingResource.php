<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Client;

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

            'name' => $this->name,
            'value' => $this->actualValue,

            'translatable' => $this->isTranslatable,

            'type' => new ProjectSettingTypeResource($this->projectSettingType),
        ];
    }
}
