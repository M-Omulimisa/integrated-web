<canvas id="myChart1" width="400"></canvas>
<script>
    $(function() {
        var ctx = document.getElementById("myChart1").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($lables); ?>,
                datasets: [{
                    label: 'Market Subscriptions',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        "#AF7AC5",
                        "#9966FF",
                        "#5D8AA8",
                        "#FF6384",
                        "#30A9DC",
                        "#FFCE56",
                        "#4BC0C0",
                        "#F8C471",
                        "#D35400",
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
