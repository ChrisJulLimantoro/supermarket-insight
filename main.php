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
                <div class="col-span-3 h-full items-center flex flex-col">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
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
                                <label data-te-select-label-ref>Select Month</label>
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
                                <label data-te-select-label-ref>Select Product</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                        <div class="text-center uppercase font-bold text-3xl mb-3" id="all"></div>
                        <div class="text-center uppercase text-lg">Total Sales</div>
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
                <div class="col-span-3 h-full flex flex-col items-center">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
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
                                <label data-te-select-label-ref>Select Month</label>
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
                                <label data-te-select-label-ref>Select Product</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                        <div class="text-center uppercase font-bold text-3xl mb-3" id="all-2"></div>
                        <div class="text-center uppercase text-lg">Transaction Count</div>
                    </div>
                </div>
                <div class="col-span-3 h-full flex items-center flex-col">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
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
                                <label data-te-select-label-ref>Select Month</label>
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
                                <label data-te-select-label-ref>Select Store</label>
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
                                <label data-te-select-label-ref>Select Category</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                        <div class="text-center uppercase font-bold text-3xl mb-3" id="all-3"></div>
                        <div class="text-center uppercase text-lg">Total Quantity of Product Sold</div>
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
                <div class="col-span-5">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Product Recommendation</h1>
                        <div class="flex flex-col w-full h-full rounded-lg p-5" id="canvas-4">
                            <canvas id="chart-4"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-span-3 h-full flex items-center flex-col">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Filter Product Recommendation</h1>
                        <div class="grid grid-rows-3 gap-2">
                            <div class="w-full">
                                <select data-te-select-init id="month_rec" class="w-full" multiple>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">Novemeber</option>
                                    <option value="12">December</option>
                                </select>
                                <label data-te-select-label-ref>Select Month</label>
                            </div>
                            <div class="w-full">
                                <select data-te-select-init id="store_rec" class="w-full" multiple>
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
                                <label data-te-select-label-ref>Select Store</label>
                            </div>
                            <div class="w-full">
                                <select data-te-select-init id="product_rec" class="w-full">
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
                                <label data-te-select-label-ref>Select Product</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                        <div class="text-center uppercase font-bold text-3xl mb-3" id="all-4"></div>
                        <div class="text-center uppercase text-lg">Percentage of product sold</div>
                    </div>
                </div>
                <div class="col-span-3 h-full flex items-center flex-col">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Filter Recommended Supplier</h1>
                        <div class="grid grid-rows-3 gap-2">
                            <div class="w-full">
                                <select data-te-select-init id="order_supp" class="w-full">
                                    <option value="leadTime" selected>By Lead Time</option>
                                    <option value="margin">By Margin</option>
                                </select>
                                <label data-te-select-label-ref>Select Order By</label>
                            </div>
                            <div class="w-full">
                                <select data-te-select-init id="ware_supp" class="w-full">
                                    <?php
                                        $sql = "SELECT warehouse_id,address FROM warehouse";
                                        $stmt = $conn_sql->prepare($sql);
                                        $stmt->execute();
                                        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($res as $r):
                                    ?>
                                    <option value="<?= $r['warehouse_id'] ?>"><?= $r['address'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label data-te-select-label-ref>Select Warehouse</label>
                            </div>
                            <div class="w-full">
                                <select data-te-select-init id="prod_supp" class="w-full">
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
                                <label data-te-select-label-ref>Select Product</label>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 w-full h-56 gap-4">
                        <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                            <div class="text-center uppercase font-bold text-2xl mb-3" id="total-5"></div>
                            <div class="text-center uppercase text-md">Count of Delivery</div>
                        </div>
                        <div class="flex flex-col w-full h-56 rounded-lg shadow-xl items-center justify-center p-5">
                            <div class="text-center uppercase font-bold text-2xl mb-3" id="price-5"></div>
                            <div class="text-center uppercase text-md">Price of Product</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-5">
                    <div class="flex flex-col w-full h-full rounded-lg shadow-xl items-center justify-center p-5">
                        <h1 class="text-center uppercase font-bold text-2xl mb-2">Recommended Supplier</h1>
                        <div class="flex flex-col w-full h-full rounded-lg p-5" id="canvas-5">
                            <canvas id="chart-5"></canvas>
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
        ajaxD($("#product_rec").val(),null,null);
        ajaxE($("#order_supp").val(),$("#ware_supp").val(),$("#prod_supp").val());

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

        // for e
        $("#order_supp").on('change',function(e){
            let filterOrder = null;
            if($('#order_supp').val() != []){
                filterOrder = $('#order_supp').val();
            }
            let filterWare = null;
            if($("#ware_supp").val() != []){
                filterWare = $("#ware_supp").val();
            }
            let filterProd = null;
            if($("#prod_supp").val() != []){
                filterProd = $("#prod_supp").val();
            }
            ajaxE(filterOrder,filterWare,filterProd);
        })

        $("#ware_supp").on('change',function(e){
            let filterOrder = null;
            if($('#order_supp').val() != []){
                filterOrder = $('#order_supp').val();
            }
            let filterWare = null;
            if($("#ware_supp").val() != []){
                filterWare = $("#ware_supp").val();
            }
            let filterProd = null;
            if($("#prod_supp").val() != []){
                filterProd = $("#prod_supp").val();
            }
            ajaxE(filterOrder,filterWare,filterProd);
        })

        $("#prod_supp").on('change',function(e){
            let filterOrder = null;
            if($('#order_supp').val() != []){
                filterOrder = $('#order_supp').val();
            }
            let filterWare = null;
            if($("#ware_supp").val() != []){
                filterWare = $("#ware_supp").val();
            }
            let filterProd = null;
            if($("#prod_supp").val() != []){
                filterProd = $("#prod_supp").val();
            }
            ajaxE(filterOrder,filterWare,filterProd);
        })

        // for d
        $("#month_rec").on('change',function(e){
            let filterMonth = null;
            if($("#month_rec").val() != []){
                filterMonth = $("#month_rec").val();
            }
            let filterStore = null;
            if($("#store_rec").val() != []){
                filterStore = $("#store_rec").val();
            }
            let filterProduct = null;
            if($("#product_rec").val() != []){
                filterProduct = $("#product_rec").val();
            }
            ajaxD(filterProduct,filterMonth,filterStore);
        })

        $("#store_rec").on('change',function(e){
            let filterMonth = null;
            if($("#month_rec").val() != []){
                filterMonth = $("#month_rec").val();
            }
            let filterStore = null;
            if($("#store_rec").val() != []){
                filterStore = $("#store_rec").val();
            }
            let filterProduct = null;
            if($("#product_rec").val() != []){
                filterProduct = $("#product_rec").val();
            }
            ajaxD(filterProduct,filterMonth,filterStore);
        })

        $("#product_rec").on('change',function(e){
            let filterMonth = null;
            if($("#month_rec").val() != []){
                filterMonth = $("#month_rec").val();
            }
            let filterStore = null;
            if($("#store_rec").val() != []){
                filterStore = $("#store_rec").val();
            }
            let filterProduct = null;
            if($("#product_rec").val() != []){
                filterProduct = $("#product_rec").val();
            }
            ajaxD(filterProduct,filterMonth,filterStore);
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
                $("#all-2").text(res.z.toLocaleString('en-US'));
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
                    console.log(res)
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
                    $("#all-3").text(res.z.toLocaleString('en-US'));
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
                    $("#all").text(convertToDollarValue(res.z));
                    new te.Chart(
                        document.getElementById('chart-options-example'),
                        dataChartOptionsExample,
                        optionsChartOptionsExample
                    );
                }
            })
        }
        function ajaxD(filterProduct=null,filterMonth=null,filterStore=null){
            let dataAjax;
            if(filterMonth == null && filterStore == null){
                dataAjax = {
                    ajax : "d",
                    filter : {
                        product : filterProduct
                    }
                }
            }else if(filterMonth == null){
                dataAjax = {
                    ajax : "d",
                    filter : {
                        store : filterStore,
                        product : filterProduct
                    }
                }
            }else if(filterStore == null){
                dataAjax = {
                    ajax : "d",
                    filter : {
                        product : filterProduct,
                        month : filterMonth
                    }
                }
            }else{
                dataAjax = {
                    ajax : "d",
                    filter : {
                        product : filterProduct,
                        month : filterMonth,
                        store : filterStore
                    }
                }
            }
            $.ajax({
                url : './analytics.php',
                method : "POST",
                data : dataAjax,
                success:function(res){
                    res = JSON.parse(res)
                    // Data
                    const dataChartFunnelExample = {
                    type: 'bar',
                    data: {
                        labels: res.x,
                        datasets: [
                        {
                            data: res.y,
                        },
                        ],
                    },
                    };
                    
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
                    // Options
                    const optionsChartFunnelExample = {
                    dataLabelsPlugin: true,
                    options: {
                        indexAxis: 'y',
                        scales: {
                        x:
                            {
                            grid: {
                                offsetGridLines: true,
                            },
                            },
                        },
                        plugins: {
                        legend: {
                            display: false,
                        },
                        datalabels: {
                            color: '#4f4f4f',
                            labels: {
                            title: {
                                font: {
                                size: '13',
                                },
                                anchor: 'end',
                                align: 'right',
                            },
                            },
                        },
                        },
                    },
                    };
        
                    const optionsDarkModeChartFunnelExample = {
                    options: {
                        scales: {
                        y: {
                            max : max,
                            beginAtZero : true,
                            ticks: {
                            color: "#fff",
                            },
                        },
                        x: {
                            ticks: {
                            color: "#fff",
                            },
                        },
                        },
                        plugins: {
                        datalabels: {
                            color: "#fff",
                        }
                        }
                    }
                    }
                    $("#all-4").text(res.z.toLocaleString('en-US') + " %");
                    $("#canvas-4").html("<canvas id='chart-4'></canvas>");
                    new te.Chart(
                        document.getElementById("chart-4"),
                        dataChartFunnelExample,
                        optionsChartFunnelExample,
                        optionsDarkModeChartFunnelExample
                    );
                }
            })
        }

        function ajaxE(filterOrder="leadTime",filterWare=null,filterProd=null){
            let dataAjax;
            dataAjax = {
                ajax : "e",
                order : filterOrder,
                filter : {
                    product : filterProd,
                    warehouse : filterWare
                }
            }
            $.ajax({
                url : './analytics.php',
                method : "POST",
                data : dataAjax,
                success: function(res){
                    console.log(res);
                    res = JSON.parse(res)
                    const dataChartDobuleYAxisExample = {
                        type: 'bar',
                        data: {
                            labels: res.x,
                            datasets: [
                            {
                                label: 'Margin ($ dollars)',
                                yAxisID: 'y',
                                data: res.y1,
                                order: 2,
                            },
                            {
                                label: 'Lead Time (days)',
                                yAxisID: 'y1',
                                data: res.y,
                                type: 'line',
                                order: 1,
                                backgroundColor: 'rgba(66, 133, 244, 0.0)',
                                borderColor: '#94DFD7',
                                borderWidth: 2,
                                pointBorderColor: '#94DFD7',
                                pointBackgroundColor: '#94DFD7',
                                lineTension: 0.0,
                            },
                            ],
                        },
                        };
        
                        // Options
                        const optionsChartDobuleYAxisExample = {
                        options: {
                            scales: {
                            y:
                                {
                                display: true,
                                position: 'left',
                                },
                            y1:
                                {
                                display: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false,
                                },
                                ticks: {
                                    beginAtZero: true,
                                },
                                },
                            },
                        },
                        };
        
                        const optionsDarkModeChartDobuleYAxisExample = {
                        options: {
                            scales: {
                            y: {
                                ticks: {
                                color: "#fff",
                                },
                            },
                            y1: {
                                ticks: {
                                color: "#fff",
                                },
                            },
                            x: {
                                ticks: {
                                color: "#fff",
                                },
                            },
                            },
                            plugins: {
                            datalabels: {
                                color: "#fff",
                            },
                            legend: {
                                labels: {
                                color: "#fff",
                                },
                            },
                            },
                        },
                        };
                        $("#canvas-5").html("<canvas id='chart-5'></canvas>");
                        $("#total-5").text(res.total.toLocaleString('en-US'));
                        $("#price-5").text(convertToDollarValue(res.price));
                        new te.Chart(
                        document.getElementById("chart-5"),
                        dataChartDobuleYAxisExample,
                        optionsChartDobuleYAxisExample,
                        optionsDarkModeChartDobuleYAxisExample
                        );
                }
            })
        }

        function convertToDollarValue(number) {
            // Convert number to string and split into parts for formatting
            let temp = number.toLocaleString('en-US')

            // Add '$' sign and concatenate parts
            return '$ ' + temp;
        }

    })
    // Data

    </script>
</html>