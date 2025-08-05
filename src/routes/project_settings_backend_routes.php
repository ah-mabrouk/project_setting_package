<?php

use Illuminate\Support\Facades\Route;
use Mabrouk\ProjectSetting\Http\Controllers\Backend\ProjectSettingGroupController;
use Mabrouk\ProjectSetting\Http\Controllers\Backend\ProjectSettingController;
use Mabrouk\ProjectSetting\Http\Controllers\Backend\ProjectSettingSectionController;

Route::group([
    'namespace' => 'Backend',
    'prefix' => 'backend',
    'middleware' => [
        'auth:api',
        'translatable',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]
], function () {
    Route::apiResource('project-setting-groups', ProjectSettingGroupController::class)->except(['destroy']);
    Route::apiResource('project-settings', ProjectSettingController::class)->except(['show', 'destroy']);
    Route::apiResource('project-setting-groups.project-setting-sections', ProjectSettingSectionController::class)->scoped();
});