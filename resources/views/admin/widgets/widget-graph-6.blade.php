<canvas id="WeatherSubscriptionsChat" width="400"></canvas>
<script>
    $(function() {
        var ctx = document.getElementById("WeatherSubscriptionsChat").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($lables); ?>,
                datasets: [{
                    label: 'Market Subscriptions',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        "#F8C471",
                        "#AF7AC5",
                        "#D35400",
                        "#9966FF",
                        "#5D8AA8",
                        "#FF6384",
                        "#30A9DC",
                        "#FFCE56",
                        "#4BC0C0",
                        "#34495E",
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
</script>
