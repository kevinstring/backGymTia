<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronJobTest extends Command
{
    protected $signature = 'cron:test';
    protected $description = 'Comando de prueba para cron job';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('¡El cron job ha funcionado!');
    }
}
