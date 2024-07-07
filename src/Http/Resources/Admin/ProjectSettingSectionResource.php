<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSettingSectionResource extends JsonResource
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

            'group' => new ProjectSettingGroupSimpleResource($this->projectSettingGroup),
            'project_settings' => ProjectSettingResource::collection($this->projectSettings),
        ];
    }
}
