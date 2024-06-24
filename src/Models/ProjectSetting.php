<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Mabrouk\Filterable\Traits\Filterable;
use Mabrouk\Translatable\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSetting extends Model
{
    use HasFactory, Translatable, Filterable;

    public $translatedAttributes = [
        'name',
        'description',
        'key_value',
    ];

    protected $fillable = [
        'project_setting_section_id',
        'project_setting_type_id',

        'key',
        'non_translatable_value',
        'custom_validation_rules',

        'is_visible',
        'is_editable',
        'is_return_to_client',
    ];

    protected $with = [
        'projectSettingType',
    ];

    ## Relations

    public function projectSettingType()
    {
        return $this->belongsTo(ProjectSettingType::class, 'project_setting_type_id');
    }

    public function projectSettingSection()
    {
        return $this->belongsTo(ProjectSettingSection::class, 'project_setting_section_id');
    }

    ## Getters & Setters

    public function getIsTranslatableAttribute()
    {
        return $this->projectSettingType->is_translatable;
    }

    public function getValueAttribute()
    {
        return $this->is_translatable ? $this->key_value : $this->non_translatable_value;
    }

    public function getValidationRuleAttribute()
    {
        return $this->custom_validation_rules ?? $this->projectSettingType->validation_rules;
    }

    ## Query Scope Methods

    public function scopeOfKey($query, $key = '')
    {
        $query->where('key', $key);
    }

    public function scopeOfType($query, int $typeId = 0)
    {
        $query->where('project_setting_type_id', $typeId);
    }

    public function scopeOfSection($query, int $sectionId = 0)
    {
        $query->where('project_setting_section_id', $sectionId);
    }

    public function scopeVisible($query, $visible = true)
    {
        $query->where('is_visible', $visible);
    }

    public function scopeEditable($query, $editable = true)
    {
        $query->where('is_editable', $editable);
    }

    public function scopeReturnToClient($query, $returnToClient = true)
    {
        $query->where('is_return_to_client', $returnToClient);
    }

    public function scopeTranslatable($query1, $translatable = true)
    {
        $query1->where(function ($query2) use ($translatable) {
            $query2->whereHas('projectSettingType', function ($query3) use ($translatable) {
                $query3->translatable($translatable);
            });
        });
    }

    ## Other Methods

}
