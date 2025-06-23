<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Mabrouk\Filterable\Traits\Filterable;
use Mabrouk\ProjectSetting\database\factories\ProjectSettingGroupFactory;
use Mabrouk\Translatable\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingGroup extends Model
{
    use HasFactory, Translatable, Filterable;

    public $translatedAttributes = [
        'name',
        'description',
    ];

    protected $fillable = [
        'id',
        'slug',

        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
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

    public function scopeVisible($query, bool $visible = true)
    {
        return $query->where('is_visible', $visible);
    }

    ## Other Methods

    public function remove()
    {
        if ($this->projectSettingSections()->count() > 0) return false;

        $this->deleteTranslations()->delete();
        return true;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return ProjectSettingGroupFactory::new();
    }    
}
