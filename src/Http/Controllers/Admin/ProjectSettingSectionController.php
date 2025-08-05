<?php

namespace Mabrouk\ProjectSetting\Http\Controllers\Admin;

use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;
use Mabrouk\ProjectSetting\Http\Controllers\Controller;
use Mabrouk\ProjectSetting\Models\ProjectSettingSection;
use Mabrouk\ProjectSetting\Filters\Admin\ProjectSettingSectionFilter;
use Mabrouk\ProjectSetting\Http\Resources\Admin\ProjectSettingSectionResource;
use Mabrouk\ProjectSetting\Http\Requests\Admin\ProjectSettingSectionStoreRequest;
use Mabrouk\ProjectSetting\Http\Requests\Admin\ProjectSettingSectionUpdateRequest;

class ProjectSettingSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProjectSettingGroup $project_setting_group
     * @param ProjectSettingSectionFilter $filters
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectSettingGroup $project_setting_group, ProjectSettingSectionFilter $filters)
    {
        $paginationLength = pagination_length(ProjectSettingSection::class);
        $projectSettingSections = $project_setting_group->projectSettingSections()
            ->filter($filters)
            ->with([
                'projectSettingGroup.translations',
                'projectSettings.projectSettingType',
                'projectSettings.phone',
                'projectSettings.media',
                'projectSettings.translations',
            ])->paginate($paginationLength);

        return ProjectSettingSectionResource::collection($projectSettingSections);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param ProjectSettingSectionStoreRequest $request
    //  * @param ProjectSettingGroup $project_setting_group
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(ProjectSettingSectionStoreRequest $request, ProjectSettingGroup $project_setting_group)
    // {
    //     $projectSettingSection = $request->storeProjectSettingSection();

    //     return response([
    //         'message' => __('mabrouk/project_settings/project_setting_sections.store'),
    //         'project_setting_section' => new ProjectSettingSectionResource($projectSettingSection),
    //     ]);
    // }

    /**
     * Display the specified resource.
     *
     * @param ProjectSettingGroup $project_setting_group
     * @param ProjectSettingSection $project_setting_section
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectSettingGroup $project_setting_group, ProjectSettingSection $project_setting_section)
    {
        $project_setting_section->load([
            'projectSettingGroup.translations',
            'projectSettings.projectSettingType',
            'projectSettings.phone',
            'projectSettings.media',
        ]);

        return response([
            'project_setting_section' => new ProjectSettingSectionResource($project_setting_section),
        ]);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param ProjectSettingSectionUpdateRequest $request
    //  * @param ProjectSettingGroup $project_setting_group
    //  * @param ProjectSettingSection $project_setting_section
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(ProjectSettingSectionUpdateRequest $request, ProjectSettingGroup $project_setting_group, ProjectSettingSection $project_setting_section)
    // {
    //     $projectSettingSection = $request->updateProjectSettingSection();

    //     return response([
    //         'message' => __('mabrouk/project_settings/project_setting_sections.update'),
    //         'project_setting_section' => new ProjectSettingSectionResource($projectSettingSection),
    //     ]);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param ProjectSettingGroup $project_setting_group
    //  * @param ProjectSettingSection $project_setting_section
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(ProjectSettingGroup $project_setting_group, ProjectSettingSection $project_setting_section)
    // {
    //     if (!$project_setting_section->remove()) {
    //         return response([
    //             'message' => __('mabrouk/project_settings/project_setting_sections.cant_destroy'),
    //         ], 409);
    //     }

    //     return response([
    //         'message' => __('mabrouk/project_settings/project_setting_sections.destroy'),
    //     ]);
    // }
}
