<?php
    require_once "connect.php";
?>
<?php
    if(isset($_POST['ajax'])){
        if($_POST['ajax'] == 'a'){
            $collectionCust = $conn_mongo->Ceje->cust_sales;
            if(!isset($_POST['filter'])){
                $pipeline = [
                    [
                        '$project' => [
                            'customer_id' => 1,
                            'sales' => ['$objectToArray' => '$sales']
                        ]
                    ],
                    [
                        '$unwind' => '$sales'
                    ],
                    [
                        '$group' => [
                            '_id' => '$customer_id',
                            'totalCombined' => ['$sum' => '$sales.v.total']
                        ]
                    ],
                    [
                        '$sort' => ['totalCombined' => -1]
                    ],
                    [
                        '$limit' => 5
                    ]
                ];
                $pipelineTotalSales = [
                    [
                        '$project' => [
                            'customer_id' => 1,
                            'sales' => ['$objectToArray' => '$sales']
                        ]
                    ],
                    [
                        '$unwind' => '$sales'
                    ],
                    [
                        '$group' => [
                            '_id' => '$customer_id',
                            'totalCombined' => ['$sum' => '$sales.v.total']
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'totalSalesAllCustomers' => ['$sum' => '$totalCombined']
                        ]
                    ]
                ];
            }else{
                if(!isset($_POST['filter']['product'])){
                    $pipeline = [
                        [
                            '$addFields' => [
                                'monthFilteredSales' => [
                                    '$filter' => [
                                        'input' => ['$objectToArray' => '$sales'],
                                        'as' => 'sale',
                                        'cond' => [
                                            '$in' => [
                                                ['$substr' => ['$$sale.v.date', 0, 2]],
                                                $_POST['filter']['month']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'totalCombined' => ['$sum' => '$monthFilteredSales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$customer_id',
                                'totalCombined' => ['$sum' => '$totalCombined']
                            ]
                        ],
                        [
                            '$sort' => ['totalCombined' => -1]
                        ],
                        [
                            '$limit' => 5
                        ]
                    ];
                    $pipelineTotalSales = [
                        [
                            '$project' => [
                                'customer_id' => 1,
                                'sales' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales'
                        ],
                        [
                            '$match' => [
                                'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months (January and February)
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$customer_id',
                                'totalCombined' => ['$sum' => '$sales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalSalesAllCustomers' => ['$sum' => '$totalCombined']
                            ]
                        ]
                    ];
                }else if(!isset($_POST['filter']['month'])){
                    $pipeline = [
                        [
                            '$addFields' => [
                                'filteredProducts' => [
                                    '$filter' => [
                                        'input' => [
                                            '$reduce' => [
                                                'input' => ['$objectToArray' => '$sales'],
                                                'initialValue' => [],
                                                'in' => [
                                                    '$concatArrays' => ['$$value', '$$this.v.products']
                                                ]
                                            ]
                                        ],
                                        'as' => 'product',
                                        'cond' => [
                                            '$in' => ['$$product.product_id', $_POST['filter']['product']] // Your product_id filter
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'totalCombined' => [
                                    '$sum' => '$filteredProducts.sub_total'
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$customer_id',
                                'totalCombined' => ['$sum' => '$totalCombined']
                            ]
                        ],
                        [
                            '$sort' => ['totalCombined' => -1]
                        ],
                        [
                            '$limit' => 5
                        ]
                    ];
                    $pipelineTotalSales = [
                        [
                            '$project' => [
                                'customer_id' => 1,
                                'sales' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales'
                        ],
                        [
                            '$match' => [
                                "sales.v.products" => [
                                    '$elemMatch' => [ 'product_id' => [ '$in' => $_POST['filter']['product'] ] ] // Replace with your selected product IDs
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$customer_id',
                                'totalCombined' => ['$sum' => '$sales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalSalesAllCustomers' => ['$sum' => '$totalCombined']
                            ]
                        ]
                    ];
                }else{
                    $pipeline = [
                        [
                            '$addFields' => [
                                'monthFilteredSales' => [
                                    '$filter' => [
                                        'input' => ['$objectToArray' => '$sales'],
                                        'as' => 'sale',
                                        'cond' => [
                                            '$in' => [
                                                ['$substr' => ['$$sale.v.date', 0, 2]],
                                                $_POST['filter']['month'] // Your filter for months (e.g., January, March, June)
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'filteredProducts' => [
                                    '$filter' => [
                                        'input' => [
                                            '$reduce' => [
                                                'input' => '$monthFilteredSales',
                                                'initialValue' => [],
                                                'in' => [
                                                    '$concatArrays' => ['$$value', '$$this.v.products']
                                                ]
                                            ]
                                        ],
                                        'as' => 'product',
                                        'cond' => [
                                            '$in' => ['$$product.product_id', $_POST['filter']['product']] // Your product_id filter
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'totalCombined' => [
                                    '$sum' => [
                                        '$concatArrays' => [
                                            ['$monthFilteredSales.v.total'],
                                            '$filteredProducts.sub_total'
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$customer_id',
                                'totalCombined' => ['$sum' => '$totalCombined']
                            ]
                        ],
                        [
                            '$sort' => ['totalCombined' => -1]
                        ],
                        [
                            '$limit' => 5
                        ]
                    ];
                    $pipelineTotalSales = [
                        [
                            '$project' => [
                                'customer_id' => 1,
                                'sales' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales'
                        ],
                        [
                            '$match' => [
                                'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months (January and February)
                                "sales.v.products" => [
                                    '$elemMatch' => [ 'product_id' => [ '$in' => $_POST['filter']['product'] ] ] // Replace with your selected product IDs
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$customer_id',
                                'totalCombined' => ['$sum' => '$sales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalSalesAllCustomers' => ['$sum' => '$totalCombined']
                            ]
                        ]
                    ];
                }
            }
            
            // Execute aggregation pipeline
            $result = $collectionCust->aggregate($pipeline);
            $all = $collectionCust->aggregate($pipelineTotalSales);
            $x = [];
            $y = [];
            $z = 0;
            foreach($all as $a){
                $z = $a['totalSalesAllCustomers'];
            }
            foreach ($result as $r){
                $mysql = "SELECT first_name,last_name FROM customer WHERE customer_id = :id";
                $stmt = $conn_sql->prepare($mysql);
                $stmt->execute([':id' => $r['_id']]);
                $name = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // echo json_encode($name);
                $x[] = $name[0]['first_name'].' '.$name[0]['last_name'];
                $y[] = $r['totalCombined'];
            }
            echo json_encode(['x' => $x, 'y' => $y, 'z' => $z]);
            exit;
        }else if($_POST['ajax'] == 'b'){
            $collectionProd = $conn_mongo->Ceje->prod_sales;
            if(!isset($_POST['filter'])){
                $pipeline = [
                    [
                        '$project' => [
                            'stores' => ['$objectToArray' => '$stores'],
                            'product_id' => 1,
                        ],
                    ],
                    ['$unwind' => '$stores'],
                    [
                        '$project' => [
                            'product_id' => 1,
                            'store_id' => '$stores.v.store_id',
                            'sales' => ['$objectToArray' => '$stores.v.sales'],
                        ],
                    ],
                    ['$unwind' => '$sales'],
                    [
                        '$group' => [
                            '_id' => '$product_id',
                            'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                        ],
                    ],
                    ['$sort' => ['totalQuantity' => -1]],
                    ['$limit' => 5],
                ];   
                // Pipeline to calculate total quantity across all documents
                $pipelineTotalQuantity = [
                    [
                        '$project' => [
                            'stores' => ['$objectToArray' => '$stores'],
                        ],
                    ],
                    ['$unwind' => '$stores'],
                    [
                        '$addFields' => [
                            'allQuantity' => [
                                '$reduce' => [
                                    'input' => ['$objectToArray' => '$stores.v.sales'],
                                    'initialValue' => 0,
                                    'in' => ['$add' => ['$$value', '$$this.v.quantity']],
                                ],
                            ],
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'totalAllQuantity' => ['$sum' => '$allQuantity'],
                        ],
                    ],
                ];             
            }else{
                if(!isset($_POST['filter']['month']) && !isset($_POST['filter']['category'])){
                    $pipeline = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => ['store_id' => ['$in' => $_POST['filter']['store']]]], // Filter by selected store IDs
                        [
                            '$group' => [
                                '_id' => '$product_id',
                                'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                        ['$sort' => ['totalQuantity' => -1]],
                        ['$limit' => 5],
                    ];
                    $pipelineTotalQuantity = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'store_id' => ['$in' => $_POST['filter']['store']], // Filter by selected store IDs
                        ]],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalAllQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                    ];
                }else if(!isset($_POST['filter']['store']) && !isset($_POST['filter']['category'])){
                    $pipeline = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'sales.v.date' => ['$regex' => '^(' . implode('|', $_POST['filter']['month']) . ')\/']
                        ]], // Filter by selected store IDs and months
                        [
                            '$group' => [
                                '_id' => '$product_id',
                                'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                        ['$sort' => ['totalQuantity' => -1]],
                        ['$limit' => 5],
                    ];
                    $pipelineTotalQuantity = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months
                        ]],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalAllQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                    ];
                }else if(!isset($_POST['filter']['store']) && !isset($_POST['filter']['month'])){
                    $prod = [];
                    foreach($_POST['filter']['category'] as $c){
                        $sql = "SELECT product_id FROM product WHERE category_id = :cat";
                        $prod_stmt = $conn_sql->prepare($sql);
                        $prod_stmt->execute([
                            ":cat" => $c
                        ]);
                        $res = $prod_stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($res as $r){
                            if(!in_array($r['product_id'],$prod)){
                                $prod[] = $r['product_id'];
                            }
                        }
                    }
                    $pipeline = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'product_id' => ['$in' => $prod],
                        ]], // Filter by selected store IDs, months, and product IDs
                        [
                            '$group' => [
                                '_id' => '$product_id',
                                'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                        ['$sort' => ['totalQuantity' => -1]],
                        ['$limit' => 5],
                    ];
                    $pipelineTotalQuantity = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'product_id' => ['$in' => $prod], // Filter by selected product IDs
                        ]],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalAllQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                    ];
                }else if(!isset($_POST['filter']['category'])){
                    $pipeline = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'store_id' => ['$in' => $_POST['filter']['store']],
                            'sales.v.date' => ['$regex' => '^(' . implode('|', $_POST['filter']['month']) . ')\/']
                        ]], // Filter by selected store IDs and months
                        [
                            '$group' => [
                                '_id' => '$product_id',
                                'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                        ['$sort' => ['totalQuantity' => -1]],
                        ['$limit' => 5],
                    ];
                    $pipelineTotalQuantity = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'store_id' => ['$in' => $_POST['filter']['store']], // Filter by selected store IDs
                            'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months
                        ]],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalAllQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                    ];
                }else if(!isset($_POST['filter']['month'])){
                    $prod = [];
                    foreach($_POST['filter']['category'] as $c){
                        $sql = "SELECT product_id FROM product WHERE category_id = :cat";
                        $prod_stmt = $conn_sql->prepare($sql);
                        $prod_stmt->execute([
                            ":cat" => $c
                        ]);
                        $res = $prod_stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($res as $r){
                            if(!in_array($r['product_id'],$prod)){
                                $prod[] = $r['product_id'];
                            }
                        }
                    }
                    $pipeline = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'store_id' => ['$in' => $_POST['filter']['store']],
                            'product_id' => ['$in' => $prod],
                        ]], // Filter by selected store IDs, months, and product IDs
                        [
                            '$group' => [
                                '_id' => '$product_id',
                                'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                        ['$sort' => ['totalQuantity' => -1]],
                        ['$limit' => 5],
                    ];
                    $pipelineTotalQuantity = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'store_id' => ['$in' => $_POST['filter']['store']], // Filter by selected store IDs
                            'product_id' => ['$in' => $prod], // Filter by selected product IDs
                        ]],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalAllQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                    ];
                }else if(!isset($_POST['filter']['store'])){
                    $prod = [];
                    foreach($_POST['filter']['category'] as $c){
                        $sql = "SELECT product_id FROM product WHERE category_id = :cat";
                        $prod_stmt = $conn_sql->prepare($sql);
                        $prod_stmt->execute([
                            ":cat" => $c
                        ]);
                        $res = $prod_stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($res as $r){
                            if(!in_array($r['product_id'],$prod)){
                                $prod[] = $r['product_id'];
                            }
                        }
                    }
                    $pipeline = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'sales.v.date' => ['$regex' => '^(' . implode('|', $_POST['filter']['month']) . ')\/'],
                            'product_id' => ['$in' => $prod],
                        ]], // Filter by selected store IDs, months, and product IDs
                        [
                            '$group' => [
                                '_id' => '$product_id',
                                'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                        ['$sort' => ['totalQuantity' => -1]],
                        ['$limit' => 5],
                    ];
                    $pipelineTotalQuantity = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months
                            'product_id' => ['$in' => $prod], // Filter by selected product IDs
                        ]],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalAllQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                    ];
                }else{
                    $prod = [];
                    foreach($_POST['filter']['category'] as $c){
                        $sql = "SELECT product_id FROM product WHERE category_id = :cat";
                        $prod_stmt = $conn_sql->prepare($sql);
                        $prod_stmt->execute([
                            ":cat" => $c
                        ]);
                        $res = $prod_stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($res as $r){
                            if(!in_array($r['product_id'],$prod)){
                                $prod[] = $r['product_id'];
                            }
                        }
                    }
                    $pipeline = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        [
                            '$match' => [
                                'store_id' => ['$in' => $_POST['filter']['store']],
                                'sales.v.date' => ['$regex' => '^(' . implode('|', $_POST['filter']['month']) . ')\/'],
                                'product_id' => ['$in' => $prod],
                            ],
                        ], // Filter by selected store IDs, months, and product IDs
                        [
                            '$group' => [
                                '_id' => '$product_id',
                                'totalQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                        ['$sort' => ['totalQuantity' => -1]],
                        ['$limit' => 5],
                    ];
                    $pipelineTotalQuantity = [
                        [
                            '$project' => [
                                'stores' => ['$objectToArray' => '$stores'],
                                'product_id' => 1,
                            ],
                        ],
                        ['$unwind' => '$stores'],
                        [
                            '$project' => [
                                'product_id' => 1,
                                'store_id' => '$stores.v.store_id',
                                'sales' => ['$objectToArray' => '$stores.v.sales'],
                            ],
                        ],
                        ['$unwind' => '$sales'],
                        ['$match' => [
                            'store_id' => ['$in' => $_POST['filter']['store']], // Filter by selected store IDs
                            'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months
                            'product_id' => ['$in' => $prod], // Filter by selected product IDs
                        ]],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalAllQuantity' => ['$sum' => '$sales.v.quantity'],
                            ],
                        ],
                    ];
                }
            }
            $result = $collectionProd->aggregate($pipeline);
            $all = $collectionProd->aggregate($pipelineTotalQuantity);
            $x = [];
            $y = [];
            $z = 0;
            foreach($all as $d){
                $z = $d['totalAllQuantity'];
            }
            foreach($result as $r){
                $mysql = "SELECT name FROM product WHERE product_id = :id";
                $stmt = $conn_sql->prepare($mysql);
                $stmt->execute([
                    ":id" => $r['_id']
                ]);
                $name = $stmt->fetchColumn();
                $x[] = substr($name,0,12);
                $y[] = $r['totalQuantity'];
            }
            echo json_encode(['x' => $x, 'y' => $y, 'z' => $z]);
            exit;
        }else if($_POST['ajax'] == 'c'){
            $collectionStore = $conn_mongo->Ceje->store_sales;
            if(!isset($_POST['filter'])){
                $pipeline = [
                    [
                        '$project' => [
                            'store_id' => 1,
                            'sales' => ['$objectToArray' => '$sales']
                        ]
                    ],
                    [
                        '$unwind' => '$sales'
                    ],
                    [
                        '$group' => [
                            '_id' => '$store_id',
                            'totalCombined' => ['$sum' => '$sales.v.total']
                        ]
                    ],
                    [
                        '$sort' => ['totalCombined' => -1]
                    ],
                    [
                        '$limit' => 5
                    ]
                ];
                $pipelineTotalSales = [
                    [
                        '$project' => [
                            'store-id' => 1,
                            'sales' => ['$objectToArray' => '$sales']
                        ]
                    ],
                    [
                        '$unwind' => '$sales'
                    ],
                    [
                        '$group' => [
                            '_id' => '$store-id',
                            'totalCombined' => ['$sum' => '$sales.v.total']
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'totalSalesAllStores' => ['$sum' => '$totalCombined']
                        ]
                    ]
                ];
            }else{
                if(!isset($_POST['filter']['product'])){
                    $pipeline = [
                        [
                            '$addFields' => [
                                'monthFilteredSales' => [
                                    '$filter' => [
                                        'input' => ['$objectToArray' => '$sales'],
                                        'as' => 'sale',
                                        'cond' => [
                                            '$in' => [
                                                ['$substr' => ['$$sale.v.date', 0, 2]],
                                                $_POST['filter']['month']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'totalCombined' => ['$sum' => '$monthFilteredSales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$store_id',
                                'totalCombined' => ['$sum' => '$totalCombined']
                            ]
                        ],
                        [
                            '$sort' => ['totalCombined' => -1]
                        ],
                        [
                            '$limit' => 5
                        ]
                    ];
                    $pipelineTotalSales = [
                        [
                            '$project' => [
                                'store_id' => 1,
                                'sales' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales'
                        ],
                        [
                            '$match' => [
                                'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months (January and February)
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$store_id',
                                'totalCombined' => ['$sum' => '$sales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalSalesAllStores' => ['$sum' => '$totalCombined']
                            ]
                        ]
                    ];
                }else if(!isset($_POST['filter']['month'])){
                    $pipeline = [
                        [
                            '$addFields' => [
                                'filteredProducts' => [
                                    '$filter' => [
                                        'input' => [
                                            '$reduce' => [
                                                'input' => ['$objectToArray' => '$sales'],
                                                'initialValue' => [],
                                                'in' => [
                                                    '$concatArrays' => ['$$value', '$$this.v.products']
                                                ]
                                            ]
                                        ],
                                        'as' => 'product',
                                        'cond' => [
                                            '$in' => ['$$product.product_id', $_POST['filter']['product']] // Your product_id filter
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'totalCombined' => [
                                    '$sum' => '$filteredProducts.sub_total'
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$store_id',
                                'totalCombined' => ['$sum' => '$totalCombined']
                            ]
                        ],
                        [
                            '$sort' => ['totalCombined' => -1]
                        ],
                        [
                            '$limit' => 5
                        ]
                    ];
                    $pipelineTotalSales = [
                        [
                            '$project' => [
                                'store_id' => 1,
                                'sales' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales'
                        ],
                        [
                            '$match' => [
                                "sales.v.products" => [
                                    '$elemMatch' => [ 'product_id' => [ '$in' => $_POST['filter']['product'] ] ] // Replace with your selected product IDs
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$store_id',
                                'totalCombined' => ['$sum' => '$sales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalSalesAllStores' => ['$sum' => '$totalCombined']
                            ]
                        ]
                    ];
                }else{
                    $pipeline = [
                        [
                            '$addFields' => [
                                'monthFilteredSales' => [
                                    '$filter' => [
                                        'input' => ['$objectToArray' => '$sales'],
                                        'as' => 'sale',
                                        'cond' => [
                                            '$in' => [
                                                ['$substr' => ['$$sale.v.date', 0, 2]],
                                                $_POST['filter']['month'] // Your filter for months (e.g., January, March, June)
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'filteredProducts' => [
                                    '$filter' => [
                                        'input' => [
                                            '$reduce' => [
                                                'input' => '$monthFilteredSales',
                                                'initialValue' => [],
                                                'in' => [
                                                    '$concatArrays' => ['$$value', '$$this.v.products']
                                                ]
                                            ]
                                        ],
                                        'as' => 'product',
                                        'cond' => [
                                            '$in' => ['$$product.product_id', $_POST['filter']['product']] // Your product_id filter
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$addFields' => [
                                'totalCombined' => [
                                    '$sum' => [
                                        '$concatArrays' => [
                                            ['$monthFilteredSales.v.total'],
                                            '$filteredProducts.sub_total'
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$store_id',
                                'totalCombined' => ['$sum' => '$totalCombined']
                            ]
                        ],
                        [
                            '$sort' => ['totalCombined' => -1]
                        ],
                        [
                            '$limit' => 5
                        ]
                    ];
                    $pipelineTotalSales = [
                        [
                            '$project' => [
                                'store_id' => 1,
                                'sales' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales'
                        ],
                        [
                            '$match' => [
                                'sales.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months (January and February)
                                "sales.v.products" => [
                                    '$elemMatch' => [ 'product_id' => [ '$in' => $_POST['filter']['product'] ] ] // Replace with your selected product IDs
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$store_id',
                                'totalCombined' => ['$sum' => '$sales.v.total']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'totalSalesAllStores' => ['$sum' => '$totalCombined']
                            ]
                        ]
                    ];
                }
            }
            
            // Execute aggregation pipeline
            $result = $collectionStore->aggregate($pipeline);
            $all = $collectionStore->aggregate($pipelineTotalSales);
            $x = [];
            $y = [];
            $z = 0;
            foreach($all as $a){
                $z = $a['totalSalesAllStores'];
            }
            foreach ($result as $r){
                $mysql = "SELECT name FROM store WHERE store_id = :id";
                $stmt = $conn_sql->prepare($mysql);
                $stmt->execute([':id' => $r['_id']]);
                $name = $stmt->fetchColumn();
                $x[] = $name;
                $y[] = $r['totalCombined'];
            }
            echo json_encode(['x' => $x, 'y' => $y,'z' => $z]);
            exit;
        }
    }
?>