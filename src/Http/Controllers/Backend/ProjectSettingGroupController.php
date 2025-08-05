<?php

namespace Mabrouk\ProjectSetting\Http\Controllers\Backend;

use Mabrouk\ProjectSetting\Filters\Backend\ProjectSettingGroupFilter;
use Mabrouk\ProjectSetting\Http\Controllers\Controller;
use Mabrouk\ProjectSetting\Http\Requests\Backend\ProjectSettingGroupStoreRequest;
use Mabrouk\ProjectSetting\Http\Requests\Backend\ProjectSettingGroupUpdateRequest;
use Mabrouk\ProjectSetting\Http\Resources\Backend\ProjectSettingGroupResource;
use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;

class ProjectSettingGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProjectSettingGroupFilter $filters
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectSettingGroupFilter $filters)
    {
        $paginationLength = pagination_length(ProjectSettingGroup::class);
        $projectSettingGroups = ProjectSettingGroup::filter($filters)->with('translations')->paginate($paginationLength);

        return ProjectSettingGroupResource::collection($projectSettingGroups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectSettingGroupStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectSettingGroupStoreRequest $request)
    {
        $projectSettingGroup = $request->storeProjectSettingGroup();

        return response([
            'message' => __('mabrouk/project_settings/project_setting_groups.store'),
            'project_setting_group' => new ProjectSettingGroupResource($projectSettingGroup),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param ProjectSettingGroup $project_setting_group
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectSettingGroup $project_setting_group)
    {
        return response([
            'project_setting_group' => new ProjectSettingGroupResource($project_setting_group),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectSettingGroupUpdateRequest $request
     * @param ProjectSettingGroup $project_setting_group
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectSettingGroupUpdateRequest $request, ProjectSettingGroup $project_setting_group)
    {
        $projectSettingGroup = $request->updateProjectSettingGroup();

        return response([
            'message' => __('mabrouk/project_settings/project_setting_groups.update'),
            'project_setting_group' => new ProjectSettingGroupResource($projectSettingGroup),
        ]);
    }
}
