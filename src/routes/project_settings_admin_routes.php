<?php

use Illuminate\Support\Facades\Route;
use Mabrouk\ProjectSetting\Http\Controllers\Admin\ProjectSettingController;
use Mabrouk\ProjectSetting\Http\Controllers\Admin\ProjectSettingTypeController;
use Mabrouk\ProjectSetting\Http\Controllers\Admin\ProjectSettingGroupController;
use Mabrouk\ProjectSetting\Http\Controllers\Admin\ProjectSettingSectionController;

Route::group([
    'namespace' => 'Admin',
    'prefix' => config('project_settings.package_admin_routes_prefix'),
    'middleware' => [
        'auth:api',
        'translatable',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]
], function () {
    Route::apiResource('project-setting-types', ProjectSettingTypeController::class)->only(['index', 'show']);
    Route::apiResource('project-setting-groups', ProjectSettingGroupController::class)->only(['index', 'show']);
    Route::apiResource('project-setting-groups.project-setting-sections', ProjectSettingSectionController::class)->only(['index', 'show'])->scoped();
    Route::apiResource('project-setting-groups.project-setting-sections.project-settings', ProjectSettingController::class)->except(['store', 'destroy'])->scoped();
});