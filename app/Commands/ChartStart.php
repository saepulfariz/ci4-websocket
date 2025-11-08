<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ChartStart extends BaseCommand
{
    protected $group       = 'chart';
    protected $name        = 'chart:start';
    protected $description = 'Menjalankan server Chart WebSocket CodeIgniter 4';

    public function run(array $params)
    {
        CLI::write('Menjalankan server WebSocket...', 'green');

        $ws = service('ChartSocket');
        // $ws->set_callback('auth', [new \App\Controllers\Websocket(), '_auth']);
        // $ws->set_callback('event', [new \App\Controllers\Websocket(), '_event']);
        // $ws->set_callback('update_chart', [new \App\Controllers\Websocket(), '_chart']);
        $ws->run();
    }
}
