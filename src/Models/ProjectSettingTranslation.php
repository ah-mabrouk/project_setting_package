<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'name',
        'description',
        'key_value',
    ];

    ## Relations

    public function projectSetting()
    {
        return $this->belongsTo(ProjectSetting::class, 'project_setting_id');
    }
}
