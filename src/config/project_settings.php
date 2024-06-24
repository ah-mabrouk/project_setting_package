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

];
