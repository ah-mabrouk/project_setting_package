<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'related_object_type',
        'related_object_id',

        'country_code',
        'number',
        'type', // Work, Home, Mobile, Landline ...etc
    ];

    ## Relations

    public function relatedObject()
    {
        return $this->morphTo();
    }

    public function country()
    {
        return $this->belongsTo(ProjectSettingCountry::class, 'country_code', 'phone_code');
    }

    ## Getters & Setters

    public function getFullAttribute()
    {
        return "{$this->country_code}{$this->number}";
    }

    public function setCountryCodeAttribute($value)
    {
        $this->attributes['country_code'] = Str::contains($value, '+') ? \str_replace('+', '00', $value) : $value;
    }

    public function setNumberAttribute($value)
    {
        if ($value) {
            $this->attributes['number'] = (int) $value;
        }
    }

    ## Scopes

    public function scopeOfData($query, array $phoneData = [])
    {
        if (! \in_array('country_code', \array_keys($phoneData)) || ! \in_array('number', \array_keys($phoneData))) return $query->where('id', -1);
        $countryCode = Str::contains($phoneData['country_code'], '+') ? \str_replace('+', '00', $phoneData['country_code']) : $phoneData['country_code'];
        $number = Str::contains($phoneData['number'], '+') ? \str_replace('+', '00', $phoneData['number']) : $phoneData['number'];
        $query->where([
            'country_code' => $countryCode,
            'number' => $number,
        ]);

        return $query;
    }

    public function scopeOfType($query, string $type = '')
    {
        return $type != '' ? $query->where('type', $type) : $query;
    }

    ## Other Methods

}
