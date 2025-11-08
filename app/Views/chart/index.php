<!DOCTYPE html>
<html>

<head>
    <title>Realtime Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h2>Realtime Chart dengan WebSocket</h2>
    <canvas id="myChart" width="400" height="200"></canvas>

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
            socket.send('get_data');
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
            socket.send('get_data');
        }

        setInterval(getData, 2000);
    </script>
</body>

</html>