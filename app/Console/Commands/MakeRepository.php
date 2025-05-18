<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository class';

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryName = preg_replace('/Repository$/', '', $name) . 'Repository';
        $path = app_path("Repositories/{$repositoryName}.php");

        if (File::exists($path)) {
            $this->error("Repository {$repositoryName} already exists!");
            return Command::FAILURE;
        }

        if (!File::exists(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'), 0755, true);
        }

        $content = <<<PHP
<?php

namespace App\Repositories;

class {$repositoryName}
{
    //
}
PHP;

        File::put($path, $content);

        $this->info("Repository {$repositoryName} created successfully.");
        return Command::SUCCESS;
    }
}
