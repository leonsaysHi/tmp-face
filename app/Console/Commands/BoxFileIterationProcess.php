<?php

namespace App\Console\Commands;

use App\BoxRepository;
use Illuminate\Console\Command;

class BoxFileIterationProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'box:iterate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the file iteration process for the files in the box';

    protected $repository;

    /**
     * Create a new command instance.
     *
     * @param BoxRepository $repository
     */
    public function __construct(BoxRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->repository->processFiles();
    }
}
