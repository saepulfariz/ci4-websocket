<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\EventLoop\Factory;
use React\Socket\Server as Reactor;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class ServerChart implements MessageComponentInterface
{
    protected $clients;
    // protected $loop;


    public function __construct()
    {
        $this->clients = new \SplObjectStorage;

        // Menginisialisasi event loop ReactPHP
        // $this->loop = Factory::create();

        // Menjalankan server ReactPHP
        // $this->loop->run();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";

        // Kirim data pertama kali setelah koneksi terbuka
        // $this->sendDataToClient($conn);

        // // Mengatur pengiriman data otomatis setiap 5 detik
        // $this->loop->addPeriodicTimer(2, function () {
        //     $this->sendDataToAllClients();
        // });
    }

    // Kirim data ke semua klien
    private function sendDataToAllClients()
    {
        foreach ($this->clients as $client) {
            $this->sendDataToClient($client);
        }
    }

    // Kirim data ke klien tertentu
    private function sendDataToClient(ConnectionInterface $client)
    {
        // Ambil data baru
        $data = (new \App\Models\ChartModel())->dummyData();

        // Kirim data ke klien
        $client->send(json_encode($data));
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

        if ($msg == "get_data_db") {

            (new \App\Models\TransactionModel())->generateData();
            $data = (new \App\Models\TransactionModel())->getChart();

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
