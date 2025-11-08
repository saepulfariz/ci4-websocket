<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ServerChart implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // echo "Message data: {$msg}\n";


        if ($msg == "get_data") {
            // Contoh: setiap kali pesan diterima, kirim data chart baru ke semua client
            // $data = [
            //     'labels' => ['A', 'B', 'C', 'D', 'E'],
            //     'values' => [
            //         rand(10, 100),
            //         rand(10, 100),
            //         rand(10, 100),
            //         rand(10, 100),
            //         rand(10, 100),
            //     ],
            // ];

            $data = (new \App\Models\ChartModel())->dummyData();

            foreach ($this->clients as $client) {
                $client->send(json_encode($data));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
