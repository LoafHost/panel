<?php

namespace LoafPanel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class UpdateCommand extends Command
{
    protected $signature = 'lp:update';
    protected $description = 'Update the Loaf Panel to the latest version.';

    public function handle()
    {
        $this->info('Starting Loaf Panel update...');

        $this->runProcess('git pull', base_path());
        $this->runProcess('composer install --no-dev --optimize-autoloader', base_path());
        $this->runProcess('php artisan migrate --force', base_path());
        $this->runProcess('php artisan view:clear', base_path());
        $this->runProcess('php artisan config:clear', base_path());
        $this->runProcess('php artisan route:clear', base_path());

        $this->info('Loaf Panel updated successfully!');
    }

    protected function runProcess($command, $directory)
    {
        $process = Process::fromShellCommandline($command, $directory);
        $process->setTimeout(3600);
        $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $buffer) {
            $this->getOutput()->write($buffer);
        });

        if (!$process->isSuccessful()) {
            Log::error("Update process failed for command: {$command}");
            $this->error("Update process failed. Check the logs for more details.");
            exit(1);
        }
    }
}
