<!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->

<canvas id="myChartBar1" style="width:100%;max-width:800px"></canvas>

<?php

include("engine/get_data_chart_dash_day.php");

?>

<script>

    const ctx = document.getElementById('myChartBar1');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels?>,
            datasets: [{
                label: "BTC",
                backgroundColor: "#025984",
                data: <?php echo $data4?>
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



