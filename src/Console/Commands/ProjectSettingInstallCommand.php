<?php

namespace Mabrouk\ProjectSetting\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Database\Seeders\ProjectSettingTypesTableSeeder;

class ProjectSettingInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and Publish Mabrouk Project Setting Package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Publishing configuration...');

        if (! $this->configExists('project_settings.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration(true);
            } else {
                $this->info('Existing configuration is not overwritten');
            }
        }

        $this->info('Caching configs...');
        $this->call('config:cache');

        $this->info('Running migrate command...');

        $this->runMigration();

        return Command::SUCCESS;
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm('Config file already exists. Do you want to overwrite it?', false);
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => 'Mabrouk\ProjectSetting\ProjectSettingServiceProvider',
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

       $this->call('vendor:publish', $params);
    }

    private function runMigration()
    {
        $this->warn('Make sure to set package configuration before migration or you will need to run this command again');
        if (! $this->confirm('Do you want to run migrate command now?', false)) return;

        $this->migratePackageTables();
    }

    private function migratePackageTables()
    {
        if (! \is_array(config('project_settings.database_connections'))) abort(500, 'wrong config key value data type. "project_settings.database_connections" key should return an array');

        $migrationSubFolder = config('project_settings.migration_sub_folder') != '' ? config('project_settings.migration_sub_folder') . '/' : '';

        if (\count(config('project_settings.database_connections')) == 0) return $this->migrateToDatabaseConnection($migrationSubFolder);

        $currentConnectionDriver = DB::connection()->getPdo()?->getAttribute(\PDO::ATTR_DRIVER_NAME) ?? config('database.default');

        foreach (config('project_settings.database_connections') as $databaseConnectionName) {
            $this->migrateToDatabaseConnection($migrationSubFolder, $databaseConnectionName);
        }

        $this->setCurrentConnectionTo($currentConnectionDriver);
    }

    private function migrateToDatabaseConnection(string $migrationSubFolder = '', string $databaseConnectionName = '')
    {
        if ($databaseConnectionName == '') {
            $this->call('migrate', ['--path' => "/database/migrations/{$migrationSubFolder}"]);
            $this->call('db:seed', ['--class' => ProjectSettingTypesTableSeeder::class]);
            return;
        }

        $this->setCurrentConnectionTo($databaseConnectionName);
        $this->call(
            'migrate',
            [
                '--database' => $databaseConnectionName,
                '--path' => "database/migrations/{$migrationSubFolder}",
            ]
        );
        $this->call('db:seed', ['--class' => ProjectSettingTypesTableSeeder::class]);
    }

    private function setCurrentConnectionTo(string $databaseConnectionName)
    {
        DB::setDefaultConnection($databaseConnectionName);
    }
}
