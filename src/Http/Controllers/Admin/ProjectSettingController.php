<?php

namespace Mabrouk\ProjectSetting\Http\Controllers\Admin;

use Mabrouk\ProjectSetting\Models\ProjectSetting;
use Mabrouk\ProjectSetting\Http\Controllers\Controller;
use Mabrouk\ProjectSetting\Filters\Admin\ProjectSettingFilter;
use Mabrouk\ProjectSetting\Http\Resources\Admin\ProjectSettingResource;
use Mabrouk\ProjectSetting\Http\Requests\Admin\ProjectSettingUpdateRequest;

class ProjectSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Mabrouk\ProjectSetting\Filters\Admin\ProjectSettingFilter  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectSettingFilter $filters)
    {
        $paginationLength = pagination_length(ProjectSetting::class);
        $projectSettings = ProjectSetting::filter($filters)->paginate($paginationLength);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Mabrouk\ProjectSetting\Http\Requests\Admin\ProjectSettingUpdateRequest  $request
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSetting  $project_setting
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectSettingUpdateRequest $request, ProjectSetting $project_setting)
    {
        $projectSettingGroup = $request->updateProjectSetting();
        return response([
            'message' => __('mabrouk/project_settings/project_settings.update'),
            'project_setting' => new ProjectSettingResource($projectSettingGroup),
        ]);
    }
}
