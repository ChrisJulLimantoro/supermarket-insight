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
            <div class="flex w-full h-24 rounded-lg shadow-xl items-center justify-center">
                <h1 class="text-center uppercase font-bold text-3xl">Supermarket Insight</h1>
            </div>
            <div class="grid grid-cols-8">
                <div class="col-span-3">
                    <div class="flex flex-col w-full h-96 rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl">Sales</h1>
                        <canvas id="chart-options-example"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </body>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>
    <script>
    // import { Chart } from "tw-elements";
        // Data
    // Data
    const dataChartOptionsExample = {
    type: 'bar',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
        datasets: [
        {
            label: 'Traffic',
            data: [30, 15, 62, 65, 61, 6],
            backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            ],
            borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            ],
            borderWidth: 1,
        },
        ],
    },
    };

    // Options
    const optionsChartOptionsExample = {
    options: {
        scales: {
        x:
            {
            ticks: {
                color: '#4285F4',
            },
            },
        y:
            {
            ticks: {
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
    </script>
</html>