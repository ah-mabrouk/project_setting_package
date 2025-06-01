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
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Publishing configuration...');

        if (!$this->configExists('project_settings.php')) {
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

    /**
     * Check if a configuration file exists.
     *
     * @param string $fileName The name of the configuration file
     * @return bool
     */
    private function configExists(string $fileName): bool
    {
        return File::exists(config_path($fileName));
    }

    /**
     * Ask user if they want to overwrite the existing configuration.
     *
     * @return bool
     */
    private function shouldOverwriteConfig(): bool
    {
        return $this->confirm('Config file already exists. Do you want to overwrite it?', false);
    }

    /**
     * Publish the package configuration.
     *
     * @param bool $forcePublish Whether to force publish the configuration
     * @return void
     */
    private function publishConfiguration(bool $forcePublish = false): void
    {
        $params = [
            '--provider' => 'Mabrouk\ProjectSetting\ProjectSettingServiceProvider',
        ];

        if ($forcePublish) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }

    /**
     * Run the migration process after confirmation.
     *
     * @return void
     */
    private function runMigration(): void
    {
        $this->warn('Make sure to set package configuration before migration or you will need to run this command again');
        if (!$this->confirm('Do you want to run migrate command now?', false)) return;

        $this->migratePackageTables();
    }

    /**
     * Migrate package tables based on configuration.
     *
     * @return void
     */
    private function migratePackageTables(): void
    {
        $dbConnections = config('project_settings.db_connections');

        if (!\is_array($dbConnections)) {
            abort(500, 'Wrong config key value data type. "project_settings.db_connections" key should return an array');
        }

        $migrationSubFolder = $this->getMigrationSubFolder();

        // If no specific connections are defined, migrate to the default connection
        if (count($dbConnections) == 0) {
            $this->migrateToDatabaseConnection($migrationSubFolder);
            return;
        }

        // Save current connection to restore it later
        $currentConnectionDriver = $this->getCurrentConnectionDriver();

        foreach ($dbConnections as $databaseConnectionName) {
            $this->migrateToDatabaseConnection($migrationSubFolder, $databaseConnectionName);
        }

        // Restore the original connection
        $this->setCurrentConnectionTo($currentConnectionDriver);
    }

    /**
     * Get the migration subfolder path from configuration.
     *
     * @return string
     */
    private function getMigrationSubFolder(): string
    {
        $subFolder = config('project_settings.migration_sub_folder');
        return !empty($subFolder) ? $subFolder . '/' : '';
    }

    /**
     * Get the current database connection driver.
     *
     * @return string
     */
    private function getCurrentConnectionDriver(): string
    {
        return DB::connection()->getPdo()?->getAttribute(\PDO::ATTR_DRIVER_NAME) ?? config('database.default');
    }

    /**
     * Migrate to a specific database connection.
     *
     * @param string $migrationSubFolder The subfolder containing migrations
     * @param string $databaseConnectionName The database connection name
     * @return void
     */
    private function migrateToDatabaseConnection(string $migrationSubFolder = '', string $databaseConnectionName = ''): void
    {
        if (\empty($databaseConnectionName)) {
            $this->migrateToDefaultConnection($migrationSubFolder);
            return;
        }

        $this->migrateToSpecificConnection($migrationSubFolder, $databaseConnectionName);
    }

    /**
     * Migrate to the default database connection.
     *
     * @param string $migrationSubFolder The subfolder containing migrations
     * @return void
     */
    private function migrateToDefaultConnection(string $migrationSubFolder): void
    {
        $this->call('migrate', ['--path' => "/database/migrations/{$migrationSubFolder}"]);
        $this->call('db:seed', ['--class' => ProjectSettingTypesTableSeeder::class]);
    }

    /**
     * Migrate to a specific database connection.
     *
     * @param string $migrationSubFolder The subfolder containing migrations
     * @param string $databaseConnectionName The database connection name
     * @return void
     */
    private function migrateToSpecificConnection(string $migrationSubFolder, string $databaseConnectionName): void
    {
        $this->setCurrentConnectionTo($databaseConnectionName);

        $this->call('migrate', [
            '--database' => $databaseConnectionName,
            '--path' => "database/migrations/{$migrationSubFolder}",
        ]);

        $this->call('db:seed', ['--class' => ProjectSettingTypesTableSeeder::class]);
    }

    /**
     * Set the current database connection.
     *
     * @param string $databaseConnectionName The database connection name
     * @return void
     */
    private function setCurrentConnectionTo(string $databaseConnectionName): void
    {
        DB::setDefaultConnection($databaseConnectionName);
    }
}
