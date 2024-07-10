<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Client;

use App\Http\Resources\Website\PhoneResource;
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

            'name' => $this->name,
            'value' => $this->when(! \in_array($this->projectSettingType->name, ['phone', 'image']), $this->value),

            'translatable' => $this->isTranslatable,

            'phone' => $this->when($this->projectSettingType->name == 'phone', new PhoneResource($this->phone)),
            'image' => $this->when($this->projectSettingType->name == 'image', new MediaResource($this->mainImage)),
            'type' => new ProjectSettingTypeResource($this->projectSettingType),
            'section' => new ProjectSettingSectionSimpleResource($this->projectSettingSection),
        ];
    }
}
