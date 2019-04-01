<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Github extends Command
{
    protected $signature = 'github';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        app('App\Http\Controllers\Console\Github')->index();
    }
}
