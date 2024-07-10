<?php

namespace Mabrouk\ProjectSetting\Traits;

use Mabrouk\ProjectSetting\Models\ProjectSettingPhone;

Trait HasPhone
{
    public function phones()
    {
        return $this->morphMany(ProjectSettingPhone::class, 'related_object')
            ->select([
                'id',
                'related_object_type',
                'related_object_id',
                'country_code',
                'number',
                'type',
            ]);
    }

    public function phone()
    {
        return $this->morphOne(ProjectSettingPhone::class, 'related_object')
            ->select([
                'id',
                'related_object_type',
                'related_object_id',
                'country_code',
                'number',
                'type',
            ]);
    }

    public function addPhone(array $data)
    {
        $keys = array_keys($data);
        if ((! array_key_exists('country_code',$data)) || (! array_key_exists('number',$data))) {
            abort(422, 'phone data must contain country_code and number attributes');
        }
        if ($data['country_code'] == null || $data['number'] == null) {
            abort(422, 'phone country_code and number attributes can\'t accept empty values');
        }

        return $this->phone()->create([
            'country_code' => $data['country_code'],
            'number' => $data['number'],
            'type' => \in_array('type', $keys) ? $data['type'] : null,
        ]);
    }

    public function editPhone(ProjectSettingPhone $phone, array $data)
    {
        $keys = array_keys($data);

        $phone->update([
            'country_code' => \in_array('country_code', $keys) ? $data['country_code'] : $phone->country_code,
            'number' => \in_array('number', $keys) ? $data['number'] : $phone->number,
            'type' => \in_array('type', $keys) ? $data['type'] : $phone->type,
        ]);
        return $phone->refresh();
    }

    public function deletePhone(ProjectSettingPhone $phone)
    {
        return $this->phones()->find($phone->id)?->delete();
    }

    public function ownsPhone(ProjectSettingPhone $phone)
    {
        return $this->phones()->where('phones.id', $phone->id)->count() > 0;
    }

    public function scopeHasPhone($query1, string $countryCode = '', string $phoneNumber = '')
    {
        return $query1->where(function ($query2) use ($countryCode, $phoneNumber) {
            $query2->whereHas('phones', function ($query3) use ($countryCode, $phoneNumber) {
                $query3->ofData([
                    'country_code' => $countryCode,
                    'number' => $phoneNumber,
                ]);
            });
        });
    }
}
