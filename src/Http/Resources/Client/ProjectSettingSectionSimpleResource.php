<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSettingSectionSimpleResource extends JsonResource
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
            'description' => $this->description,

            'project_setting_group' => new ProjectSettingGroupSimpleResource($this->projectSettingGroup),
        ];
    }
}
