<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class WebsocketStart extends BaseCommand
{
    protected $group       = 'websocket';
    protected $name        = 'websocket:start';
    protected $description = 'Menjalankan server WebSocket CodeIgniter 4';

    public function run(array $params)
    {
        CLI::write('Menjalankan server WebSocket...', 'green');

        $ws = service('CodeigniterWebsocket');
        $ws->set_callback('auth', [new \App\Controllers\Websocket(), '_auth']);
        $ws->set_callback('event', [new \App\Controllers\Websocket(), '_event']);
        $ws->run();
    }
}
