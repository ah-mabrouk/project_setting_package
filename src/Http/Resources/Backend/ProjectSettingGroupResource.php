<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Backend;

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
            'slug' => $this->slug,

            'name' => $this->name,
            'description' => $this->description,

            'visible' => $this->is_visible,

            // 'sections' => ProjectSettingSectionResource::collection($this->projectSettingSections),
        ];
    }
}
