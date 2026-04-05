<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/RuleEngine/{$name}.php");

        if (File::exists($path)) {
            $this->error('Service already exists!');
            return;
        }

        File::ensureDirectoryExists(app_path('Services/RuleEngine'));

        File::put($path, $this->template($name));

        $this->info("Service {$name} created successfully.");
    }

    protected function template($name)
    {
        return <<<PHP
<?php

namespace App\Services\RuleEngine;

class {$name}
{
    // TODO: isi algoritma Rule-Based IF–THEN di sini
}
PHP;
    }
}