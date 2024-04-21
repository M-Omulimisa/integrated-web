<canvas id="myChart16" width="400"></canvas>
<script>
    $(function() {
        var ctx = document.getElementById("myChart16").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($lables),
                datasets: [
                    {
                        label: 'Weather Subscriptions',
                        type: 'bar',
                        data: <?php echo json_encode($data_weather); ?>,
                        borderColor: "#34495E",
                        backgroundColor: "#34495E", 
                        borderWidth: 3,
                    },
                ]

            },

        });
    });
</script>
