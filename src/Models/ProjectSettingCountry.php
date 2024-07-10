<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Mabrouk\Filterable\Traits\Filterable;
use Mabrouk\Translatable\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingCountry extends Model
{
    use HasFactory, Translatable, Filterable;

    protected $fillable = [
        'code',
        'phone_code',
        'emoji_flag',
    ];

    ## Relations

    public function phones()
    {
        return $this->hasMany(ProjectSettingPhone::class, 'country_code', 'phone_code');
    }

    ## Getters & Setters

    ## Scopes

    ## Other Methods

}
