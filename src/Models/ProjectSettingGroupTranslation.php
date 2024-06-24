<?php

namespace Mabrouk\ProjectSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectSettingGroupTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'name',
    ];

    ## Relations

    public function projectSettingGroup()
    {
        return $this->belongsTo(ProjectSettingGroup::class, 'project_setting_group_id');
    }
}
