<canvas id="myChart13" width="400"></canvas>
<script>
    $(function() {
        var ctx = document.getElementById("myChart13").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($lables),
                datasets: [
                    {
                        label: 'Insurance Subscriptions',
                        type: 'line',
                        data: <?php echo json_encode($data_insurance); ?>,
                        borderColor: "#34495E",
                        backgroundColor: 'rgba(75, 192, 192, 0.0)',
                        borderWidth: 3,
                    },
                ]

            },

        });
    });
</script>
