<canvas id="myChart" width="400"></canvas>
<script>
    $(function() {
        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: '#277C61',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        var chartData = {
            labels: ['date 1', 'date 2', 'date 3', 'date 4', 'date 5', 'date 6'],
            datasets: [{
                type: 'line',
                label: 'Beans',
                borderColor: window.chartColors.red,
                borderWidth: 3,
                data: [33, 53, 85, 41, 44, 65],
                fill: false,
            }, {
                type: 'line',
                label: 'Maize',
                borderColor: window.chartColors.blue,
                borderWidth: 3,
                data: [33, 53, 85, 41, 44, 65],
                fill: false,
            }, {
                type: 'line',
                label: 'Rice',
                borderColor: window.chartColors.orange,
                borderWidth: 3,
                data: [34, 54, 86, 42, 45, 66],
                fill: false,
            }, {
                label: 'Sorghum',
                data: [43, 65, 78, 45, 56, 76],
                fill: false,
            }]
        };

        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: true
                    }
                }
            }
        });

    });
</script>
