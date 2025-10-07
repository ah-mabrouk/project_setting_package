<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DB connections
    |--------------------------------------------------------------------------
    |
    | Here you should set array of string values to match the database names which will contain the project
    | settings data if you have multiple databases in your project. IF this value is set to an empty array
    | then the project settings database connection name will be set to config('database.default').
    |
    | NOTE: If you gave a custom string value here then you need to make sure that you
    | have the needed configuration set in laravel project "database" config file.
    |
    */

    # eg: 'db_connections' => ['mysql', 'additional_connection_name],
    'db_connections' => [],

    /*
    |--------------------------------------------------------------------------
    | migration sub folder
    |--------------------------------------------------------------------------
    |
    | Here you should set string value to match the folder which will contain
    | the project settings migration files if it exists in nested directorey.
    | IF this value is set to an empty string then the project settings
    | migration files will exists in the same default migration
    | directory after publishing.
    |
    */

    # eg: 'migration_sub_folder' => 'project_settings/',
    'migration_sub_folder' => '',

    /*
    |--------------------------------------------------------------------------
    | Package routes prefix
    |--------------------------------------------------------------------------
    |
    | Here you may prefer to union the usage of your project routes with a global
    | prefix. Define this prefered prefix here and access package predefined
    | routes under the same project global prefix to union the output
    | of your apis.
    |
    */
   'package_routes_prefix' => 'api',

    /*
    |--------------------------------------------------------------------------
    | Admin routes prefix
    |--------------------------------------------------------------------------
    |
    | Here you may prefer to specify additional prefix to separate admin routes under
    | specific prefix. Define this prefered prefix here.
    |
    */
   'package_admin_routes_prefix' => 'admin-panel',

    /*
    |--------------------------------------------------------------------------
    | Client routes prefix
    |--------------------------------------------------------------------------
    |
    | Here you may prefer to specify additional prefix to separate client routes under
    | specific prefix. Define this prefered prefix here.
    |
    */
   'package_client_routes_prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Routes publish subdirectory
    |--------------------------------------------------------------------------
    |
    | Here you may specify the subdirectory where the package routes should be
    | published inside the project's routes folder. This allows you to customize the location of the published
    | routes files.
    |
    */
    # eg: 'routes_publish_subdirectory' => 'custom/',
    'routes_publish_subdirectory' => '',

    /*
    |--------------------------------------------------------------------------
    | Load Routes
    |--------------------------------------------------------------------------
    |
    | This option controls whether the package routes should be loaded.
    | Set this value to true to load the routes, or false to disable them.
    |
    */
    'load_routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache Prefix
    |--------------------------------------------------------------------------
    |
    | For multi-tenant apps, cache keys should be tenant-aware. Provide a
    | string literal prefix here (e.g. 'tenant-123', 'acme.com', etc.).
    | The final cache key will be "{cache_prefix}:{cache_key_base}".
    |
    | Leave null or empty to disable tenant prefixing.
    */
    'cache_prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Cache key base
    |--------------------------------------------------------------------------
    |
    | The base portion for project settings cache keys. The final cache key will
    | be composed as: "{cache_prefix}:{base}" when cache_prefix is set,
    | otherwise it will be simply "{base}".
    */
    'cache_key_base' => 'project_settings',
];
