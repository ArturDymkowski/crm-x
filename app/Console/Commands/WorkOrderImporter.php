<?php

namespace App\Console\Commands;

use App\Http\Controllers\StartController;
use App\Modules\WorkOrder\Repositories\WorkOrderRepository;
use Illuminate\Console\Command;

class WorkOrderImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workOrder:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from HTML file to database';

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
        \App::call('App\Http\Controllers\StartController@ordersWorkerImporter');
        return 0;
    }
}
