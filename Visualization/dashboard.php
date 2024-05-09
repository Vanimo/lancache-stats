<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        function refreshData()
        {
            fetchAndUpdateData();
        }

        setInterval(refreshData, 5000); // Fetch data every 60 seconds

        function fetchAndUpdateData()
        {
            $.ajax({
                type: "POST",
                url: "queryData.php",
                dataType: "json",
                success: function(data) {
                    parseData(data);
                }
            });
        }        

        function parseData(result) {
            var GBUsed = result.GBUsed;
            var GBFree = result.GBFree;
            var labelvalues = result.labelvalues;
            var datavalues = result.datavalues;
            var totalHits = result.totalHits;
            var totalMiss = result.totalMiss;
            var GBServed = result.GBServed;
            var labelvalues4 = result.labelvalues4;
            var datavalues4 = result.datavalues4;
            var IPvalue = result.IPvalue;
            var GBValuep = result.GBValuep;

            var data1 = {
            labels: ['Used', 'Free'],
            datasets: [{
                data: [<?= $GBUsed ?>, <?= $GBFree ?>],
                backgroundColor: ['#FF9800', '#4CAF50'],
                hoverBackgroundColor: ['#FF9800', '#4CAF50']
                }]
            };
            var chartData2 = {
                labels: labelvalues,
                datasets: [{
                    data: datavalues
                }]
            };

            var data3 = {
                labels: ['Served', 'Cached'],
                datasets: [{
                    data: [<?= $totalHits ?>, <?= $GBUsed ?>],
                    backgroundColor: ['#4CAF50', '#FF9800'],
                    hoverBackgroundColor: ['#4CAF50', '#FF9800']
                }]
            };

            var chartData4 = {
                labels: labelvalues4,
                datasets: [{
                    data: datavalues4
                }]
            };
            var options4 = {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            };

            if (window.chart1) {window.chart1.destroy();}
            var ctx1 = document.getElementById('pieChart1').getContext('2d');
            window.chart1 = new Chart(ctx1, {
                type: 'pie',
                data: data1
            });

            if (window.chart2) {window.chart2.destroy();}
            var ctx2 = document.getElementById('pieChart2').getContext('2d');
            window.chart2 = new Chart(ctx2, {
                type: 'pie',
                data: chartData2
            });
            
            if (window.chart3) {window.chart3.destroy();}
            var ctx3 = document.getElementById('pieChart3').getContext('2d');
            window.chart3 = new Chart(ctx3, {
                type: 'pie',
                data: data3
            });
            
            if (window.chart4) {window.chart4.destroy();}
            var ctx4 = document.getElementById('pieChart4').getContext('2d');
            window.chart4 = new Chart(ctx4, {
                type: 'pie',
                data: chartData4,
                options: options4
            });
        }
    });
    </script>
    <style>
        .row {
            height: 50vh;
        }

        .pie-chart {
            width: 250px;
            height: 250px;
            margin: auto;
        }

        .text-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .text-container h3 {
            position: absolute;
            top: 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col text-center">
                <h3>Cache Disk Status (GiB)</h3>
                <canvas id="pieChart1" class="pie-chart"></canvas>
            </div>
            <div class="col text-center">
                <h3>Served by Upstream (GiB)</h3>
                <canvas id="pieChart2" class="pie-chart"></canvas>
            </div>
            <div class="col text-center">
                <h3>Cache Ratio (GiB)</h3>
                <canvas id="pieChart3" class="pie-chart"></canvas>
            </div>
        </div>

        <div class="row">
            <hr>
            <div class="col text-center">
                <div class="text-container">
                    <h3>Cache Statistics</h3>
                    <h5>Added to Cache</h5>
                    <h4>
                        <?= $GBUsed ?> GB
                    </h4>
                    <hr>
                    <h5>Served from Cache</h5>
                    <h4>
                        <?= $GBServed ?> GB
                    </h4>
                </div>
            </div>
            <div class="col text-center">
                <h3>Served by Game GB</h3>
                <canvas id="pieChart4" class="pie-chart"></canvas>
            </div>
            <div class="col text-center">
                <div class="text-container">
                    <h3>Served IPs</h3>
                    <p>
                        <?php for ($i = 0; $i < count($IPvalue); $i++) {
                            echo $IPvalue[$i] . ' ==> ' . number_format($GBValuep[$i], 2) . ' GB<BR>';
                        } ?>
                    </p>
                </div>
            </div>
        </div>

    </div>
    <script>
        var data1 = {
            labels: ['Used', 'Free'],
            datasets: [{
                data: [<?= $GBUsed ?>, <?= $GBFree ?>],
                backgroundColor: ['#FF9800', '#4CAF50'],
                hoverBackgroundColor: ['#FF9800', '#4CAF50']
            }]
        };
        var labels2 = <?php echo json_encode($labelvalues); ?>;
        var data2 = <?php echo json_encode($datavalues); ?>;
        var chartData2 = {
            labels: labels2,
            datasets: [{
                data: data2
            }]
        };

        var data3 = {
            labels: ['Served', 'Cached'],
            datasets: [{
                data: [<?= $totalHits ?>, <?= $GBUsed ?>],
                backgroundColor: ['#4CAF50', '#FF9800'],
                hoverBackgroundColor: ['#4CAF50', '#FF9800']
            }]
        };

        var labels4 = <?php echo json_encode($labelvalues4); ?>;
        var data4 = <?php echo json_encode($datavalues4); ?>;
        var chartData4 = {
            labels: labels4,
            datasets: [{
                data: data4
            }]
        };
        var options4 = {
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true
                }
            }
        };

        var ctx1 = document.getElementById('pieChart1').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: data1
        });

        var ctx2 = document.getElementById('pieChart2').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: chartData2
        });
        var ctx3 = document.getElementById('pieChart3').getContext('2d');
        new Chart(ctx3, {
            type: 'pie',
            data: data3
        });
        var ctx4 = document.getElementById('pieChart4').getContext('2d');
        new Chart(ctx4, {
            type: 'pie',
            data: chartData4,
            options: options4
        });
    </script>
</body>

</html>