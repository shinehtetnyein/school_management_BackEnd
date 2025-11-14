<?php

namespace Modules\Subject\Console\Commands;

use Illuminate\Console\Command;
use Modules\Subject\App\Models\Subject;
use Illuminate\Support\Str;

class GenerateSubjectsUuids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subjects:generate-uuids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate UUIDs for subjects that do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subjectsWithoutUuid = Subject::whereNull('uuid')->get();

        if ($subjectsWithoutUuid->isEmpty()) {
            $this->info('✓ All subjects already have UUIDs.');
            return 0;
        }

        $this->info(sprintf('Generating UUIDs for %d subjects...', $subjectsWithoutUuid->count()));

        $progressBar = $this->output->createProgressBar($subjectsWithoutUuid->count());
        $progressBar->start();

        foreach ($subjectsWithoutUuid as $subject) {
            $subject->update(['uuid' => (string) Str::uuid()]);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info(sprintf('✓ Successfully generated UUIDs for %d subjects!', $subjectsWithoutUuid->count()));

        return 0;
    }
}
