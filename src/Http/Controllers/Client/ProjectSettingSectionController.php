<?php

namespace Mabrouk\ProjectSetting\Http\Controllers\Client;

use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;
use Mabrouk\ProjectSetting\Http\Controllers\Controller;
use Mabrouk\ProjectSetting\Models\ProjectSettingSection;
use Mabrouk\ProjectSetting\Filters\Client\ProjectSettingSectionFilter;
use Mabrouk\ProjectSetting\Http\Resources\Client\ProjectSettingSectionResource;

class ProjectSettingSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingGroup  $project_setting_group
     * @param  \Mabrouk\ProjectSetting\Filters\Client\ProjectSettingSectionFilter  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectSettingGroup $project_setting_group, ProjectSettingSectionFilter $filters)
    {
        $paginationLength = pagination_length(ProjectSettingSection::class);
        $projectSettingSections = $project_setting_group->projectSettingSections()->filter($filters)->paginate($paginationLength);
        return ProjectSettingSectionResource::collection($projectSettingSections);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingGroup  $project_setting_group
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingSection  $project_setting_section
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectSettingGroup $project_setting_group, ProjectSettingSection $project_setting_section)
    {
        return response([
            'project_setting_section' => new ProjectSettingSectionResource($project_setting_section),
        ]);
    }
}
