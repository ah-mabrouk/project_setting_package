<?php

namespace Mabrouk\ProjectSetting\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Mabrouk\ProjectSetting\Models\ProjectSettingType;

class ProjectSettingTypeUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:types-update ?{--existing_types}';

    public Collection $existingProjectSettingTypesData;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing project setting types';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(array $existing_types = [])
    {
        $this->existingProjectSettingTypesData = collect($existing_types);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (\count($this->existingProjectSettingTypesData) == 0) {
            $this->info('No specific project settings types to update.');
            return;
        }

        $existingProjectSettingTypesNames = \implode("\n", $this->existingProjectSettingTypesData->pluck('name')->flatten()->toArray());

        $this->warn("Project setting types database table contains this list of types: \n [ \n {$existingProjectSettingTypesNames} \n ]");

        if (! $this->confirm('Do you want to update this list of project setting types?', false)) return;

        $this->info('Updating the mentioned list of project setting types...');

        ProjectSettingType::whereIn('name', $this->existingProjectSettingTypesData->pluck('name')->flatten()->toArray())->get()
            ->map(function ($type) {
                $this->updateProjectSettingType($type);
            });

        return Command::SUCCESS;
    }

    protected function updateProjectSettingType(ProjectSettingType $type)
    {
        $type->update(
            $this->existingProjectSettingTypesData->where('name', $type->name)->first()->toArray()
        );
    }
}
