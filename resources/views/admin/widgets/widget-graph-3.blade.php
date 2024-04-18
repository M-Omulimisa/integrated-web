<canvas id="myChart12" width="400"></canvas>
<script>
    $(function() {
        var ctx = document.getElementById("myChart12").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($lables),
                datasets: [{
                    label: 'Market Subscriptions',
                    type: 'line',
                    data: <?php echo json_encode($data_market); ?>,
                    borderColor: "green",
                    backgroundColor: 'rgba(75, 192, 192, 0.0)',
                    borderWidth: 3,
                }, ]

            },

        });
    });
</script>
