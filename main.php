<?php require_once "./connect.php"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once "./head.php"; ?>
    </head>
    <body>
        <!-- <div class="flex"> -->
        <?php require_once "./sidenav.php"; ?>
        <!-- <div class="grid grid-cols-12"> -->
        <div class="ml-60 px-8 py-3">
            <div class="flex w-full h-24 rounded-lg shadow-xl items-center justify-center mb-8">
                <h1 class="text-center uppercase font-bold text-3xl">Supermarket Insight</h1>
            </div>
            <div class="grid grid-cols-8 gap-4">
                <div class="col-span-4">
                    <div class="flex flex-col w-full h-96 rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl">Top 5 Store</h1>
                        <select data-te-select-init id="month_store" class="w-full" multiple>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">Novemeber</option>
                            <option value="12">December</option>
                        </select>
                        <div class="flex flex-col w-full h-full rounded-lg p-5" id="canvas">
                            <canvas id="chart-options-example"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-span-4">
                    <div class="flex flex-col w-full h-96 rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl">Top 5 Customer</h1>
                        <select data-te-select-init id="month_cust" class="w-full" multiple>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">Novemeber</option>
                            <option value="12">December</option>
                        </select>
                        <div class="flex flex-col w-full h-full rounded-lg p-5" id="canvas-2">
                            <canvas id="chart-options-example-2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </body>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>
    <script>
    $(document).ready(function(){
        let dataChartOptionsExample = "";
        let optionsChartOptionsExample = "";
        let instance;
        $.ajax({
            url : "./analytics.php",
            method : "POST",
            data : {
                ajax : 'c',
            },
            success : function(res){
                console.log(res)
                res = JSON.parse(res)
                let min = 3000000;
                let max = 0;
                Object.entries(res.y).forEach(([key, value]) => {
                    if(value < min) min = value;
                    if(value > max) max = value;
                });
                dataChartOptionsExample = {
                    type: 'bar',
                    data: {
                        labels: res.x,
                        datasets: [
                        {
                            data: res.y,
                            backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            ],
                            borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            ],
                            borderWidth: 1,
                        },
                        ],
                    },
                };

                    // Options
                optionsChartOptionsExample = {
                options: {
                    scales: {
                        x: {
                            ticks: {
                                color: '#4285F4',
                            },
                        },
                        y: {
                            beginAtZero: false, // Ensure it doesn't start from zero
                            min: (min-30000), // Set the minimum value to 2,500,000
                            max: max, // Set the maximum value to 3,000,000
                            stepSize: 20000, // Set the scale increment to 50,000
                            ticks: {
                                callback: function (value, index, values) {
                                    return '$' + value.toLocaleString('en-US');
                                },
                                color: '#f44242',
                            },
                        },
                    },
                },
                };
                new te.Chart(
                    document.getElementById('chart-options-example'),
                    dataChartOptionsExample,
                    optionsChartOptionsExample
                );
            }
        })

        $.ajax({
            url : "./analytics.php",
            method : "POST",
            data : {
                ajax : 'a',
            },
            success : function(res){
                console.log(res)
                res = JSON.parse(res)
                let min = 3000000;
                let max = 0;
                Object.entries(res.y).forEach(([key, value]) => {
                    if(value < min) min = value;
                    if(value > max) max = value;
                });
                dataChartOptionsExample = {
                    type: 'bar',
                    data: {
                        labels: res.x,
                        datasets: [
                        {
                            data: res.y,
                            backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            ],
                            borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            ],
                            borderWidth: 1,
                        },
                        ],
                    },
                };

                    // Options
                optionsChartOptionsExample = {
                options: {
                    scales: {
                        x: {
                            ticks: {
                                color: '#4285F4',
                            },
                        },
                        y: {
                            beginAtZero: false, // Ensure it doesn't start from zero
                            min: (min-30000), // Set the minimum value to 2,500,000
                            max: max, // Set the maximum value to 3,000,000
                            stepSize: 20000, // Set the scale increment to 50,000
                            ticks: {
                                callback: function (value, index, values) {
                                    return '$' + value.toLocaleString('en-US');
                                },
                                color: '#f44242',
                            },
                        },
                    },
                },
                };
                new te.Chart(
                    document.getElementById('chart-options-example-2'),
                    dataChartOptionsExample,
                    optionsChartOptionsExample
                );
            }
        })

        $("#month_store").on('change',function(e){
            $.ajax({
            url : "./analytics.php",
            method : "POST",
            data : {
                ajax : 'c',
                filter : $(this).val()
            },
            success : function(res){
                res = JSON.parse(res)
                let min = 3000000;
                let max = 0;
                Object.entries(res.y).forEach(([key, value]) => {
                    if(value < min) min = value;
                    if(value > max) max = value;
                });

                // console.log(min,max)
                dataChartOptionsExample = {
                    type: 'bar',
                    data: {
                        labels: res.x,
                        datasets: [
                        {
                            data: res.y,
                            backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            ],
                            borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            ],
                            borderWidth: 1,
                        },
                        ],
                    },
                };

                    // Options
                optionsChartOptionsExample = {
                options: {
                    scales: {
                        x: {
                            ticks: {
                                color: '#4285F4',
                            },
                        },
                        y: {
                            beginAtZero: false, // Ensure it doesn't start from zero
                            min: (min - 20000), 
                            max: (max + 20000), 
                            stepSize: 20000, // Set the scale increment to 50,000
                            ticks: {
                                callback: function (value, index, values) {
                                    return '$' + value.toLocaleString('en-US');
                                },
                                color: '#f44242',
                            },
                        },
                    },
                },
                };
                // let existingInstance = te.Chart.getInstance(document.getElementById('chart-options-example'));
                // // Check if an instance already exists
                // if (existingInstance) {
                //     // Dispose of the existing instance
                //     existingInstance.destroy();
                // }
                $("#canvas").html("<canvas id='chart-options-example'></canvas>");
                new te.Chart(
                    document.getElementById('chart-options-example'),
                    dataChartOptionsExample,
                    optionsChartOptionsExample
                );
            }
        })
        })

        $("#month_cust").on('change',function(e){
            $.ajax({
            url : "./analytics.php",
            method : "POST",
            data : {
                ajax : 'a',
                filter : $(this).val()
            },
            success : function(res){
                res = JSON.parse(res)
                let min = 3000000;
                let max = 0;
                Object.entries(res.y).forEach(([key, value]) => {
                    if(value < min) min = value;
                    if(value > max) max = value;
                });

                // console.log(min,max)
                dataChartOptionsExample = {
                    type: 'bar',
                    data: {
                        labels: res.x,
                        datasets: [
                        {
                            data: res.y,
                            backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            ],
                            borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            ],
                            borderWidth: 1,
                        },
                        ],
                    },
                };

                    // Options
                optionsChartOptionsExample = {
                options: {
                    scales: {
                        x: {
                            ticks: {
                                color: '#4285F4',
                            },
                        },
                        y: {
                            beginAtZero: false, // Ensure it doesn't start from zero
                            min: (min - 20000), 
                            max: (max + 20000), 
                            stepSize: 20000, // Set the scale increment to 50,000
                            ticks: {
                                callback: function (value, index, values) {
                                    return '$' + value.toLocaleString('en-US');
                                },
                                color: '#f44242',
                            },
                        },
                    },
                },
                };
                // let existingInstance = te.Chart.getInstance(document.getElementById('chart-options-example'));
                // // Check if an instance already exists
                // if (existingInstance) {
                //     // Dispose of the existing instance
                //     existingInstance.destroy();
                // }
                $("#canvas-2").html("<canvas id='chart-options-example-2'></canvas>");
                new te.Chart(
                    document.getElementById('chart-options-example-2'),
                    dataChartOptionsExample,
                    optionsChartOptionsExample
                );
            }
        })
        })
    })
    // Data

    </script>
</html>