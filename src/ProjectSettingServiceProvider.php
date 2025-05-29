<?php

namespace Mabrouk\ProjectSetting;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Mabrouk\ProjectSetting\Console\Commands\ProjectSettingInstallCommand;
use Mabrouk\ProjectSetting\Console\Commands\ProjectSettingPublishRoutesCommand;
use Mabrouk\ProjectSetting\Console\Commands\ProjectSettingTypeUpdateCommand;

class ProjectSettingServiceProvider extends ServiceProvider
{
    private $packageMigrations = [
        'create_project_setting_countries_table',
        'create_project_setting_groups_table',
        'create_project_setting_group_translations_table',
        'create_project_setting_phones_table',
        'create_project_setting_sections_table',
        'create_project_setting_section_translations_table',
        'create_project_setting_types_table',
        'create_project_settings_table',
        'create_project_setting_translations_table',
        'add_is_displayed_to_project_settings_table',
    ];

    private $packageSeeders = [
        'ProjectSettingGroupsTableSeeder',
        'ProjectSettingSectionsWithSettingItemsSeeder',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        require_once __DIR__ . '/Helpers/ProjectSettingHelperFunctions.php';

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {

            $this->commands([
                ProjectSettingInstallCommand::class,
                ProjectSettingTypeUpdateCommand::class,
                ProjectSettingPublishRoutesCommand::class,
            ]);

            /**
             * Migrations
             */
            $migrationFiles = $this->migrationFiles();
            if (\count($migrationFiles) > 0) {
                $this->publishes($migrationFiles, 'project_setting_migrations');
            }

            /**
             * Seeders
             */
            $seedersFiles = $this->seedersFiles();
            if (\count($seedersFiles) > 0) {
                $this->publishes($seedersFiles, 'project_setting_seeders');
            }

            /**
             * Config and static translations
             */
            $this->publishes([
                __DIR__ . '/config/project_settings.php' => config_path('project_settings.php'), // ? Config
                __DIR__ . '/resources/lang' => App::langPath(), // ? Static translations
            ]);
        }
    }

    protected function registerRoutes()
    {
        if (config('project_settings.load_routes')) {
            Route::group($this->routeConfiguration(), function () {
                $this->loadRoutesFrom(__DIR__ . '/routes/project_settings_admin_routes.php');
                $this->loadRoutesFrom(__DIR__ . '/routes/project_settings_client_routes.php');
                $this->loadRoutesFrom(__DIR__ . '/routes/project_settings_backend_routes.php');
            });
        }
    }

    protected function routeConfiguration()
    {
        return [
            'namespace' => 'Mabrouk\ProjectSetting\Http\Controllers',
            'prefix' => config('project_settings.package_routes_prefix'),
        ];
    }

    protected function migrationFiles()
    {
        $migrationFiles = [];

        foreach ($this->packageMigrations as $migrationName) {
            if (! $this->migrationExists($migrationName)) {
                $migrationFiles[__DIR__ . "/database/migrations/{$migrationName}.php.stub"] = database_path('migrations/' . date('Y_m_d_His', time()) . "_{$migrationName}.php");
            }
        }
        return $migrationFiles;
    }

    protected function seedersFiles()
    {
        $seedersFiles = [];

        foreach ($this->packageSeeders as $seederName) {
            if (! $this->seederExists($seederName)) {
                $seedersFiles[__DIR__ . "/database/seeders/{$seederName}.php.stub"] = database_path("seeders/{$seederName}.php");
            }
        }
        return $seedersFiles;
    }

    /**
     * Check if a file exists in a directory by partial name match.
     *
     * @param string $path The directory path to search in
     * @param string $fileName The partial file name to search for
     * @return bool
     */
    protected function fileExistsInDirectory(string $path, string $fileName): bool
    {
        $files = \scandir($path);

        foreach ($files as $value) {
            if (\strpos($value, $fileName) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a migration exists.
     *
     * @param string $migrationName The migration name to check
     * @return bool
     */
    protected function migrationExists(string $migrationName): bool
    {
        return $this->fileExistsInDirectory(database_path('migrations/'), $migrationName);
    }

    /**
     * Check if a seeder exists.
     *
     * @param string $seederName The seeder name to check
     * @return bool
     */
    protected function seederExists(string $seederName): bool
    {
        return $this->fileExistsInDirectory(database_path('seeders/'), $seederName);
    }
}
