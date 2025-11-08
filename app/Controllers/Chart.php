<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Chart extends Controller
{
    public function data()
    {
        // Data dummy, nanti bisa diganti dari model/database
        $data = [
            'labels' => ['A', 'B', 'C', 'D', 'E'],
            'values' => [
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
            ],
        ];

        return $this->response->setJSON($data);
    }
    public function index()
    {
        return view('chart/index');
        // return view('chart_realtime');
    }
}
