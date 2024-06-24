<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSettingGroupResource extends JsonResource
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

            'sections' => ProjectSettingSectionResource::collection($this->projectSettingSections),
        ];
    }
}
