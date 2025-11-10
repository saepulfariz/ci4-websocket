<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Realtime Chart Database</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .chartMenu {
            width: 100vw;
            height: 40px;
            background: #1A1A1A;
            color: rgba(54, 162, 235, 1);
        }

        .chartMenu p {
            padding: 10px;
            font-size: 20px;
        }

        .chartCard {
            width: 100vw;
            height: calc(100vh - 40px);
            background: rgba(54, 162, 235, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chartBox {
            width: 700px;
            padding: 20px;
            border-radius: 20px;
            border: solid 3px rgba(54, 162, 235, 1);
            background: white;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="chartMenu">
        <p>Realtime Chart dengan WebSocket Database (Chart JS <span id="chartVersion"></span>)</p>
    </div>
    <div class="chartCard">
        <div class="chartBox">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['A', 'B', 'C', 'D', 'E'],
                datasets: [{
                    label: 'Nilai Realtime',
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                }]
            },
        });

        const socket = new WebSocket('ws://localhost:8282');

        socket.onopen = () => {
            console.log('WebSocket connected');
            // bisa trigger pertama kali minta data
            socket.send('get_data_db');
        };

        socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            // console.log(data);

            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.values;
            chart.update();
        };

        socket.onclose = () => {
            console.log('WebSocket closed');
            window.location.reload();
        };

        function getData() {
            socket.send('get_data_db');
        }

        setInterval(getData, 2000);
    </script>
</body>

</html>