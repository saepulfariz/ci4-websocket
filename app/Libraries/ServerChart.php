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

        // Kirim data pertama kali setelah koneksi terbuka
        $this->sendDataToClient($conn);

        // Jalankan pengiriman data otomatis dalam interval 5 detik
        // $this->sendDataToAllClientsAtInterval();
    }

    private function sendDataToClient(ConnectionInterface $client)
    {
        // Ambil data baru
        $data = (new \App\Models\ChartModel())->dummyData();

        // Kirim data ke client
        $client->send(json_encode($data));
    }

    private function sendDataToAllClientsAtInterval()
    {
        // Loop dengan sleep untuk interval pengiriman data
        while (true) {
            // Kirim data ke semua klien
            foreach ($this->clients as $client) {
                $this->sendDataToClient($client);
            }

            // Tunggu selama 5 detik sebelum mengirimkan data lagi
            sleep(5);
        }
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
