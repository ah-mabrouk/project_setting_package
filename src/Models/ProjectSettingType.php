<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Mabrouk\Filterable\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingType extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'validation_rules',

        'is_translatable',
    ];

    protected $casts = [
        'is_translatable' => 'boolean',
    ];

    ## Relations

    public function projectSettings()
    {
        return $this->hasMany(ProjectSetting::class, 'project_setting_type_id');
    }

    ## Getters & Setters

    ## Query Scope Methods

    public function scopeTranslatable($query, $translatable = true)
    {
        $query->where('is_translatable', $translatable);
    }

    ## Other Methods
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return \Mabrouk\ProjectSetting\Database\Factories\ProjectSettingTypeFactory::new();
    }
}
