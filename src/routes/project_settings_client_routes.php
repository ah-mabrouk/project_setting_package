<?php

use Illuminate\Support\Facades\Route;
// use Mabrouk\ProjectSetting\Http\Controllers\Client\ProjectSettingTypeController;
// use Mabrouk\ProjectSetting\Http\Controllers\Client\ProjectSettingGroupController;
// use Mabrouk\ProjectSetting\Http\Controllers\Client\ProjectSettingSectionController;

Route::group([
    'namespace' => 'Client',
    'prefix' => config('project_settings.package_client_routes_prefix'),
    'middleware' => [
        'translatable',
    ]
], function () {
    // Route::apiResource('project-setting-types', ProjectSettingTypeController::class)->only(['index', 'show']);
    // Route::apiResource('project-setting-groups', ProjectSettingGroupController::class);
    // Route::apiResource('project-setting-groups.project-setting-sections', ProjectSettingSectionController::class)->only(['index', 'show'])->scoped();
    Route::apiResource('project-settings', ProjectSettingController::class)->only(['index', 'show']);
});