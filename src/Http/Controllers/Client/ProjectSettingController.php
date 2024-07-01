<?php

namespace Mabrouk\ProjectSetting\Http\Controllers\Client;

use Mabrouk\ProjectSetting\Models\ProjectSetting;
use Mabrouk\ProjectSetting\Http\Controllers\Controller;
use Mabrouk\ProjectSetting\Filters\Client\ProjectSettingFilter;
use Mabrouk\ProjectSetting\Http\Resources\Client\ProjectSettingResource;

class ProjectSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Mabrouk\ProjectSetting\Filters\Client\ProjectSettingFilter  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectSettingFilter $filters)
    {
        $paginationLength = pagination_length(ProjectSetting::class);
        $projectSettings = ProjectSetting::returnToClient()->visible()->filter($filters)->paginate($paginationLength);
        return ProjectSettingResource::collection($projectSettings);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSetting  $project_setting
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectSetting $project_setting)
    {
        return response([
            'project_setting' => new ProjectSettingResource($project_setting),
        ]);
    }
}
