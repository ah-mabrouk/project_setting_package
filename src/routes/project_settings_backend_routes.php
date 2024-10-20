<?php

use Illuminate\Support\Facades\Route;
use Mabrouk\ProjectSetting\Http\Controllers\Backend\ProjectSettingController;

Route::group([
    'namespace' => 'Backend',
    'prefix' => 'backend',
    'middleware' => [
        'auth:api',
        'translatable',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ]
], function () {
    Route::apiResource('project-settings', ProjectSettingController::class)->only(['index', 'update']);
});