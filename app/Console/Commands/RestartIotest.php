<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestartIotest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iotest:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'iotest restart';

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
        //

    }
}
