<?php

//include("config/connect_db.php");
include('engine/get_data_chart_dash_year.php');

?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script-->
<!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->

<canvas id="myChart" style="width:100%;max-width:800px"></canvas>

<script>

    const xValues = [
        'มกราคม',
        'กุมภาพันธ์',
        'มีนาคม',
        'เมษายน',
        'พฤษภาคม',
        'มิถุนายน',
        'กรกฎาคม',
        'สิงหาคม',
        'กันยายน',
        'ตุลาคม',
        'พฤศจิกายน',
        'ธันวาคม',
    ];

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                label: 'BTC',
                data: <?php echo $data1?>,
                backgroundColor: "#800136",
                fill: false
            }]
        },
        options: {
            legend: {
                display: true,
                labels: {
                    color: 'rgb(250,155,174)'
                }
            }
        }
    });
</script>