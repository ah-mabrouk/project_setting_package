<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Mabrouk\Filterable\Traits\Filterable;
use Mabrouk\Translatable\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingGroup extends Model
{
    use HasFactory, Translatable, Filterable;

    public $translatedAttributes = [
        'name',
    ];

    protected $fillable = [
        'id',
        'slug',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    ## Relations

    public function projectSettingSections()
    {
        return $this->hasMany(ProjectSettingSection::class, 'project_setting_group_id');
    }

    public function projectSettings()
    {
        return $this->hasManyThrough(ProjectSetting::class, ProjectSettingSection::class, 'project_setting_group_id', 'project_setting_section_id');
    }

    ## Getters & Setters

    ## Query Scope Methods

    ## Other Methods

    public function remove()
    {
        if ($this->projectSettingSections()->count() > 0) return false;

        $this->deleteTranslations()->delete();
        return true;
    }
}
