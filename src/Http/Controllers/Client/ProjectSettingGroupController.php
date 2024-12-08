<?php

namespace Mabrouk\ProjectSetting\Http\Controllers\Client;

use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;
use Mabrouk\ProjectSetting\Http\Controllers\Controller;
use Mabrouk\ProjectSetting\Filters\Client\ProjectSettingGroupFilter;
use Mabrouk\ProjectSetting\Http\Resources\Client\ProjectSettingGroupResource;

class ProjectSettingGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Mabrouk\ProjectSetting\Filters\Client\ProjectSettingGroupFilter  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectSettingGroupFilter $filters)
    {
        $paginationLength = pagination_length(ProjectSettingGroup::class);
        $projectSettingGroups = ProjectSettingGroup::filter($filters)->with(['projectSettingSections'])->paginate($paginationLength);
        return ProjectSettingGroupResource::collection($projectSettingGroups);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingGroup  $project_setting_group
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectSettingGroup $project_setting_group)
    {
        return response([
            'project_setting_group' => new ProjectSettingGroupResource($project_setting_group),
        ]);
    }
}
