<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingSectionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'name',
        'description',
    ];

    ## Relations

    public function projectSettingSection()
    {
        return $this->belongsTo(ProjectSettingSection::class, 'project_setting_section_id');
    }
}
