<!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->

<canvas id="myChartBar2A" style="width:100%;max-width:800px"></canvas>

<?php

include("engine/get_data_chart_dash_day_bysale.php");

?>

<script>

    const ctx = document.getElementById('myChartBar2A');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels?>,
            datasets: [{
                label: "BTC",
                backgroundColor: "#03401f",
                data: <?php echo $data2?>
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>



