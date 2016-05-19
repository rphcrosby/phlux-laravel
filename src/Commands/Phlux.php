<?php

namespace Phlux\Laravel\Commands;

use Illuminate\Console\Command;
use Phlux\Server\Parser;
use Phlux\Server\Server;

class Phlux extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phlux';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a phlux state server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $server = new Server($this->app['phlux'], new Parser);
        $server->start();
    }
}
