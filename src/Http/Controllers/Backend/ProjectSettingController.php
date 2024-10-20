<?php

namespace Mabrouk\ProjectSetting\Http\Controllers\Backend;

use Mabrouk\ProjectSetting\Models\ProjectSetting;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;
use Mabrouk\ProjectSetting\Http\Controllers\Controller;
use Mabrouk\ProjectSetting\Models\ProjectSettingSection;
use Mabrouk\ProjectSetting\Filters\Backend\ProjectSettingFilter;
use Mabrouk\ProjectSetting\Http\Resources\Backend\ProjectSettingResource;
use Mabrouk\ProjectSetting\Http\Requests\Backend\ProjectSettingUpdateRequest;

class ProjectSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingGroup  $project_setting_group
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingSection  $project_setting_section
     * @param  \Mabrouk\ProjectSetting\Filters\Backend\ProjectSettingFilter  $filters
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectSettingGroup $project_setting_group, ProjectSettingSection $project_setting_section, ProjectSettingFilter $filters)
    {
        $paginationLength = pagination_length(ProjectSetting::class);
        $projectSettings = ProjectSetting::filter($filters)->paginate($paginationLength);
        return ProjectSettingResource::collection($projectSettings);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Mabrouk\ProjectSetting\Http\Requests\Backend\ProjectSettingUpdateRequest  $request
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingGroup  $project_setting_group
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSettingSection  $project_setting_section
     * @param  \Mabrouk\ProjectSetting\Models\ProjectSetting  $project_setting
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectSettingUpdateRequest $request, ProjectSettingGroup $project_setting_group, ProjectSettingSection $project_setting_section, ProjectSetting $project_setting)
    {
        $projectSettingGroup = $request->updateProjectSetting();
        return response([
            'message' => __('mabrouk/project_settings/project_settings.update'),
            'project_setting' => new ProjectSettingResource($projectSettingGroup),
        ]);
    }
}