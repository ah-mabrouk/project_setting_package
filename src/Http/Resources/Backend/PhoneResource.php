<?php

namespace Mabrouk\ProjectSetting\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
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
            'country_code' => $this->country_code,
            'number' => $this->number,
            'extension' => $this->extension,
            'type' => $this->type,
            'holder_name' => $this->holder_name,
        ];
    }
}
