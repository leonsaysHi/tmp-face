<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * @codeCoverageIgnore
 */
class SetupDeploymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat:setup_deployment {--cleanup= : should we
        delete all the files or do you need to run build stack after this}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will setup the initial stack build files into your app. cleanup ';

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
     * @return mixed
     */
    public function handle()
    {
        //checkout github for our app
        $command =
            "git clone --depth 1 --single-branch git@github.com:pfizer/laravel-platform-build-scripts.git deploy_temp";
        $this->info("Getting repo");
        if (\File::exists(base_path("deploy_temp"))) {
            $this->info("No git clone needed deploy_temp exists");
        } else {
            exec($command, $output, $results);
            if ($results != 0) {
                $this->error("Error getting git repo");
                return;
            }
            \File::deleteDirectory(base_path("deploy_temp/.git"));
        }

        $files_root = [
            ".travis.yml",
            "docker-compose.yml",
            ".env.travis",
            ".env.staging",
            ".env.production",
        ];

        foreach ($files_root as $file) {
            $this->info("Moving into app $file");
            if (File::exists(base_path("deploy_temp/$file"))) {
                \File::move(base_path("deploy_temp/$file"), base_path($file));
            }
        }

        if (\File::exists(base_path("deploy_temp"))) {
            $this->info("Moving Deploy folder over and deleting deploy_temp");
            File::moveDirectory(base_path("deploy_temp/deploy"), base_path("deploy"));
            File::deleteDirectory(base_path("deploy_temp"));
        } else {
            $this->info("No deploy_temp so not moving anything");
        }

        $command = "bash deploy/cleanup.sh";
        if ($this->option('cleanup')) {
            $this->info("Cleaning up files not needed since we are not building the stack just getting it setup");
            exec($command, $output, $results);
            if ($results != 0) {
                $this->error("Error cleaning up");
                return;
            }
        } else {
            $this->info("Not cleaning up since you need to run
            build but you can pass --cleanup to clear out unneeded files or $command");
        }
    }
}
