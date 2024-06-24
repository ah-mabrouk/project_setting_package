<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Mabrouk\Filterable\Traits\Filterable;
use Mabrouk\Translatable\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingSection extends Model
{
    use HasFactory, Translatable, Filterable;

    public $translatedAttributes = [
        'name',
        'description',
    ];

    protected $fillable = [
        'id',
        'project_setting_group_id',
    ];

    protected $with = [
        'projectSettings',
    ];

    ## Relations

    public function group()
    {
        return $this->belongsTo(ProjectSettingGroup::class, 'project_setting_group_id');
    }

    public function projectSettings()
    {
        return $this->hasMany(ProjectSetting::class, 'project_setting_section_id');
    }

    ## Getters & Setters

    ## Query Scope Methods

    public function scopeOfGroup($query, int $groupId = 0)
    {
        $query->where('project_setting_group_id', $groupId);
    }

    ## Other Methods

    public function remove()
    {
        if ($this->projectSettings()->count() > 0) return false;

        $this->deleteTranslations()->delete();
        return true;
    }
}
