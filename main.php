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
                <div class="col-span-3 h-full items-center flex">
                    <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Filter Top 5 Store</h1>
                        <div class="grid grid-rows-2 gap-4">
                            <div class="w-full">
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
                            </div>
                            <div class="col">
                                <select data-te-select-init id="product_store" class="w-full" multiple>
                                    <?php
                                        $sql = "SELECT product_id,name FROM product";
                                        $stmt = $conn_sql->prepare($sql);
                                        $stmt->execute();
                                        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($res as $r):
                                            ?>
                                    <option value="<?= $r['product_id'] ?>"><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-5">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Top 5 Store</h1>
                        <div class="flex flex-col w-full h-full rounded-lg p-5" id="canvas">
                            <canvas id="chart-options-example"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-span-5">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
                    <h1 class="text-center uppercase font-bold text-2xl mb-2">Top 5 Customer</h1>
                        <div class="flex flex-col w-full h-full rounded-lg p-5" id="canvas-2">
                            <canvas id="chart-options-example-2"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-span-3 h-full flex items-center">
                    <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Filter Top 5 Customer</h1>
                        <div class="grid grid-rows-2 gap-4">
                            <div class="w-full">
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
                            </div>
                            <div class="w-full">
                                <select data-te-select-init id="product_cust" class="w-full" multiple>
                                    <?php
                                        $sql = "SELECT product_id,name FROM product";
                                        $stmt = $conn_sql->prepare($sql);
                                        $stmt->execute();
                                        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($res as $r):
                                            ?>
                                    <option value="<?= $r['product_id'] ?>"><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-3 h-full flex items-center">
                    <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Filter Top 5 Product</h1>
                        <div class="grid grid-rows-3 gap-2">
                            <div class="w-full">
                                <select data-te-select-init id="month_prod" class="w-full" multiple>
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
                            </div>
                            <div class="w-full">
                                <select data-te-select-init id="store_prod" class="w-full" multiple>
                                    <?php
                                        $sql = "SELECT store_id,name FROM store";
                                        $stmt = $conn_sql->prepare($sql);
                                        $stmt->execute();
                                        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($res as $r):
                                    ?>
                                    <option value="<?= $r['store_id'] ?>"><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="w-full">
                                <select data-te-select-init id="cat_prod" class="w-full" multiple>
                                    <?php
                                        $sql = "SELECT category_id,name FROM category";
                                        $stmt = $conn_sql->prepare($sql);
                                        $stmt->execute();
                                        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($res as $r):
                                    ?>
                                    <option value="<?= $r['category_id'] ?>"><?= $r['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-5">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Top 5 Product</h1>
                        <div class="flex flex-col w-full h-full rounded-lg p-5" id="canvas-3">
                            <canvas id="chart-options-example-3"></canvas>
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
        ajaxA(null,null);
        ajaxB(null,null);
        ajaxC(null,null);

        $("#month_store").on('change',function(e){
            let filterMonth = null;
            if($(this).val() != []){
                filterMonth = $(this).val();
            }
            let filterProduct = null;
            if($("#product_store").val() != []){
                filterProduct = $("#product_store").val();
            }
            ajaxC(filterMonth,filterProduct);
        })

        $("#month_cust").on('change',function(e){
            let filterMonth = null;
            if($(this).val() != []){
                filterMonth = $(this).val();
            }
            let filterProduct = null;
            if($("#product_cust").val() != []){
                filterProduct = $("#product_cust").val();
            }
            ajaxA(filterMonth,filterProduct);
        })

        // for filter of product
        $("#product_store").on('change',function(e){
            let filterMonth = null;
            if($("#month_store").val() != []){
                filterMonth = $("#month_store").val();
            }
            let filterProduct = null;
            if($(this).val() != []){
                filterProduct = $(this).val();
            }
            ajaxC(filterMonth,filterProduct);
        })

        $("#product_cust").on('change',function(e){
            let filterMonth = null;
            if($("#month_cust").val() != []){
                filterMonth = $("#month_cust").val();
            }
            let filterProduct = null;
            if($(this).val() != []){
                filterProduct = $(this).val();
            }
            ajaxA(filterMonth,filterProduct);
        })

        $("#store_prod").on('change',function(e){
            let filterStore = null;
            if($("#store_prod").val() != []){
                filterStore = $("#store_prod").val();
            }
            let filterMonth = null;
            if($("#month_prod").val() != []){
                filterMonth = $("#month_prod").val();
            }
            let filterCat = null;
            if($("#cat_prod").val() != []){
                filterCat = $("#cat_prod").val();
            }
            ajaxB(filterMonth, filterStore, filterCat);
        })

        $("#month_prod").on('change',function(e){
            let filterStore = null;
            if($("#store_prod").val() != []){
                filterStore = $("#store_prod").val();
            }
            let filterMonth = null;
            if($("#month_prod").val() != []){
                filterMonth = $("#month_prod").val();
            }
            let filterCat = null;
            if($("#cat_prod").val() != []){
                filterCat = $("#cat_prod").val();
            }
            ajaxB(filterMonth, filterStore, filterCat);
        })

        $("#cat_prod").on('change',function(e){
            let filterStore = null;
            if($("#store_prod").val() != []){
                filterStore = $("#store_prod").val();
            }
            let filterMonth = null;
            if($("#month_prod").val() != []){
                filterMonth = $("#month_prod").val();
            }
            let filterCat = null;
            if($("#cat_prod").val() != []){
                filterCat = $("#cat_prod").val();
            }
            ajaxB(filterMonth, filterStore, filterCat);
        })

        // Function ajax for A
        function ajaxA(filterMonth=null,filterProduct=null){
            let dataAjaxA;
            if(filterMonth == null){
                if(filterProduct == null){
                    dataAjaxA = {
                        ajax : 'a',
                    }
                }else{
                    dataAjaxA = {
                        ajax : 'a',
                        filter : {
                            product : filterProduct
                        }
                    }
                }
            }else{
                if(filterProduct == null){
                    dataAjaxA = {
                        ajax : 'a',
                        filter : {
                            month : filterMonth
                        }
                    }
                }else{
                    dataAjaxA = {
                        ajax : 'a',
                        filter : {
                            month : filterMonth,
                            product : filterProduct
                        }
                    }
                }
            }
            $.ajax({
            url : "./analytics.php",
            method : "POST",
            data : dataAjaxA,
            success : function(res){
                res = JSON.parse(res)
                let min = 3000000;
                let max = 0;
                Object.entries(res.y).forEach(([key, value]) => {
                    if(value < min) min = value;
                    if(value > max) max = value;
                });
                let x = 0;
                if(min > 200000){
                    x = 20000
                }else if(min > 20000){
                    x = 5000
                }else if(min > 2000){
                    x = 500
                }else if(min > 200){
                    x = 50
                }else{
                    x = 20
                }
                min -= x
                if (min < 0) min = 0
                max += x
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
                            min: min, 
                            max: max, 
                            stepSize: x, // Set the scale increment to 50,000
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
        }

        // Function ajax for problem b
        function ajaxB(filterMonth=null,filterStore=null,filterCat=null){
            let dataAjax;
            if(filterMonth == null && filterStore == null, filterCat == null){
                dataAjax = {
                    ajax : "b"
                }
            }else if(filterMonth == null && filterStore == null){
                dataAjax = {
                    ajax : "b",
                    filter : {
                        category : filterCat
                    }
                }
            }else if(filterMonth == null && filterCat == null){
                dataAjax = {
                    ajax : "b",
                    filter : {
                        store : filterStore
                    }
                }
            }else if(filterStore == null && filterCat == null){
                dataAjax = {
                    ajax : "b",
                    filter : {
                        month : filterMonth
                    }
                }
            }else if(filterMonth == null){
                dataAjax = {
                    ajax : "b",
                    filter : {
                        store : filterStore,
                        category : filterCat
                    }
                }
            }else if(filterCat == null){
                dataAjax = {
                    ajax : "b",
                    filter : {
                        store : filterStore,
                        month : filterMonth
                    }
                }
            }else if(filterStore == null){
                dataAjax = {
                    ajax : "b",
                    filter : {
                        category : filterCat,
                        month : filterMonth
                    }
                }
            }else{
                dataAjax = {
                    ajax : "b",
                    filter : {
                        category : filterCat,
                        month : filterMonth,
                        store : filterStore
                    }
                }
            }
            $.ajax({
                url : "./analytics.php",
                method : "POST",
                data : dataAjax,
                success : function(res){
                    res = JSON.parse(res)
                    let min = 3000000;
                    let max = 0;
                    Object.entries(res.y).forEach(([key, value]) => {
                        if(value < min) min = value;
                        if(value > max) max = value;
                    });
                    let x = 0;
                    if(min > 200000){
                        x = 20000
                    }else if(min > 20000){
                        x = 5000
                    }else if(min > 2000){
                        x = 500
                    }else if(min > 200){
                        x = 50
                    }else{
                        x = 20
                    }
                    min -= x
                    if (min < 0) min = 0
                    max += x

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
                                min: min, 
                                max: max, 
                                stepSize: x, // Set the scale increment to 50,000
                                ticks: {
                                    color: '#f44242',
                                },
                            },
                        },
                    },
                    };
                    $("#canvas-3").html("<canvas id='chart-options-example-3'></canvas>");
                    new te.Chart(
                        document.getElementById('chart-options-example-3'),
                        dataChartOptionsExample,
                        optionsChartOptionsExample
                    );
                }
            })
        }

        // Function ajax for problem c
        function ajaxC(filterMonth=null,filterProduct=null){
            let dataAjax;
            if(filterMonth == null){
                if(filterProduct == null){
                    dataAjax = {
                        ajax : 'c',
                    }
                }else{
                    dataAjax = {
                        ajax : 'c',
                        filter : {
                            product : filterProduct
                        }
                    }
                }
            }else{
                if(filterProduct == null){
                    dataAjax = {
                        ajax : 'c',
                        filter : {
                            month : filterMonth
                        }
                    }
                }else{
                    dataAjax = {
                        ajax : 'c',
                        filter : {
                            month : filterMonth,
                            product : filterProduct
                        }
                    }
                }
            }
            $.ajax({
                url : "./analytics.php",
                method : "POST",
                data : dataAjax,
                success : function(res){
                    res = JSON.parse(res)
                    let min = 3000000;
                    let max = 0;
                    Object.entries(res.y).forEach(([key, value]) => {
                        if(value < min) min = value;
                        if(value > max) max = value;
                    });
                    let x = 0;
                    if(min > 200000){
                        x = 20000
                    }else if(min > 20000){
                        x = 5000
                    }else if(min > 2000){
                        x = 500
                    }else if(min > 200){
                        x = 50
                    }else{
                        x = 20
                    }
                    min -= x
                    if (min < 0) min = 0
                    max += x

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
                                min: min, 
                                max: max, 
                                stepSize: x, // Set the scale increment to 50,000
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
                    $("#canvas").html("<canvas id='chart-options-example'></canvas>");
                    new te.Chart(
                        document.getElementById('chart-options-example'),
                        dataChartOptionsExample,
                        optionsChartOptionsExample
                    );
                }
            })
        }

    })
    // Data

    </script>
</html>