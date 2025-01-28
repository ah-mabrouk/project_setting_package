<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Support\Facades\Cache;
use Mabrouk\Mediable\Traits\Mediable;
use Illuminate\Database\Eloquent\Model;
use Mabrouk\Filterable\Traits\Filterable;
use Mabrouk\ProjectSetting\Traits\HasPhone;
use Mabrouk\Translatable\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSetting extends Model
{
    use HasFactory, Translatable, Filterable, HasPhone, Mediable;

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
        'is_admin_has_display_control',
        'is_displayed',
    ];

    protected $with = [
        'projectSettingType',
    ];

    public function getRouteKeyName()
    {
        return 'key';
    }

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
        return $this->custom_validation_rules != null ? $this->custom_validation_rules : $this->projectSettingType->validation_rules;
    }

    public function getMainImageAttribute()
    {
        return $this->mainMedia;
    }

    public function getClientValueAttribute()
    {
        return $this->is_displayed ? $this->value : null;
    }

    ## Query Scope Methods

    public function scopeOfKey($query, $key = '')
    {
        $query->where('key', $key);
    }

    public function scopeOfKeys($query, array $keys = [])
    {
        $query->whereIn('key', $keys);
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

    public static function cache(bool $force = false)
    {
        if ($force) {
            self::forgetCache();
            Cache::rememberForever('project_settings', function () {
                return self::with('translations', 'phone', 'media')->get();
            });
        }
        return Cache::has('project_settings') ? Cache::get('project_settings') : self::cache(true);
    }

    public static function forgetCache(): void
    {
        Cache::forget('project_settings');
    }

    public static function key($key, string $locale = 'en', bool $asInt = false, bool $withoutTags = true)
    {
        $locale = request()->header('X-locale') ?? $locale;
        $keyObject = self::cache()->where('key', $key)->first();
        
        switch (true) {
            case !$keyObject : 
                return null;

            case $keyObject->projectSettingType->name == 'phone' :
                return $keyObject->phone;

            case $keyObject->projectSettingType->name == 'image' :
                return $keyObject->mainImage; 

            case $keyObject->non_translatable_value == null :
                $value = $keyObject->tr('key_value', $locale) ?? $keyObject->tr('key_value', config('translatable.fallback_locale'));
                return $withoutTags ? \strip_tags($value) : $value;

            case $keyObject->non_translatable_value != null :
                return $asInt ? (int) $keyObject->non_translatable_value : $keyObject->non_translatable_value;
                
            default:
                return null;
        }
    }

    public static function keys(array $keys, $locale = 'en')
    {
        return collect($keys)->mapWithKeys(function($settingKey) use ($locale) {
            return [
                $settingKey => self::key(key: $settingKey, locale: $locale)
            ];
        })->toArray();
    }
    
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return \Mabrouk\ProjectSetting\Database\Factories\ProjectSettingFactory::new();
    }
}
