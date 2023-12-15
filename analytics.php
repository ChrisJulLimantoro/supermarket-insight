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
                $pipelineCountSales = [
                    [
                        '$project' => [
                            'customer_id' => 1,
                            'sales_ids' => ['$objectToArray' => '$sales']
                        ]
                    ],
                    [
                        '$unwind' => '$sales_ids'
                    ],
                    [
                        '$group' => [
                            '_id' => [
                                'customer_id' => '$customer_id',
                                'sales_id' => '$sales_ids.k'
                            ],
                            'count' => ['$sum' => 1]
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => '$_id.customer_id',
                            'total_sales_ids' => ['$sum' => '$count']
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => null,
                            'count' => ['$sum' => '$total_sales_ids']
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
                    $pipelineCountSales = [
                        [
                            '$project' => [
                                'customer_id' => 1,
                                'sales_ids' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales_ids'
                        ],
                        [
                            '$match' => [
                                'sales_ids.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months (January and February)
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'customer_id' => '$customer_id',
                                    'sales_id' => '$sales_ids.k'
                                ],
                                'count' => ['$sum' => 1]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id.customer_id',
                                'total_sales_ids' => ['$sum' => '$count']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'count' => ['$sum' => '$total_sales_ids']
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
                    $pipelineCountSales = [
                        [
                            '$project' => [
                                'customer_id' => 1,
                                'sales_ids' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales_ids'
                        ],
                        [
                            '$match' => [
                                'sales_ids.v.products.product_id' => ['$in' => $_POST['filter']['product']] // Replace with your selected product IDs
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'customer_id' => '$customer_id',
                                    'sales_id' => '$sales_ids.k'
                                ],
                                'count' => ['$sum' => 1]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id.customer_id',
                                'total_sales_ids' => ['$sum' => '$count']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'count' => ['$sum' => '$total_sales_ids']
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
                    $pipelineCountSales = [
                        [
                            '$project' => [
                                'customer_id' => 1,
                                'sales_ids' => ['$objectToArray' => '$sales']
                            ]
                        ],
                        [
                            '$unwind' => '$sales_ids'
                        ],
                        [
                            '$match' => [
                                'sales_ids.v.date' => ['$regex' => '^(?:' . implode('|', $_POST['filter']['month']) . ')\/'], // Filter by selected months (January and February)
                                'sales_ids.v.products.product_id' => ['$in' => $_POST['filter']['product']] // Replace with your selected product IDs
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    'customer_id' => '$customer_id',
                                    'sales_id' => '$sales_ids.k'
                                ],
                                'count' => ['$sum' => 1]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id.customer_id',
                                'total_sales_ids' => ['$sum' => '$count']
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => null,
                                'count' => ['$sum' => '$total_sales_ids']
                            ]
                        ]
                    ];

                }
            }

            // Execute aggregation pipeline
            $result = $collectionCust->aggregate($pipeline);
            $all = $collectionCust->aggregate($pipelineCountSales);
            $x = [];
            $y = [];
            $z = 0;
            foreach($all as $a){
                $z = $a['count'];
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
        }else if($_POST['ajax'] == 'd'){
            if(!isset($_POST['filter']['store']) && !isset($_POST['filter']['month'])){
                $cypher = <<< CYPHER
                    MATCH (p1:Product)-[]->(s:Sales)<-[]-(p2:Product)
                    MATCH (st:Store)-[]-(s)
                    WHERE p1.product_id = '{$_POST['filter']['product']}' AND p1 <> p2
                    RETURN p2.product_id, COUNT(p2) as countProduct, COLLECT(s.sales_id), COLLECT(st.store_id)
                    ORDER BY countProduct DESC
                    LIMIT 5
                CYPHER;
                $cypher2 = <<< CYPHER
                    MATCH (p:Product)-[r]->(s:Sales)-[]-(st:Store)
                    WHERE p.product_id = '{$_POST['filter']['product']}'
                    WITH COUNT(p) as p
                    MATCH (t:Sales)-[]-(st:Store)
                    WITH p, COUNT(t) as t
                    RETURN toFloat(p)/toFloat(t)*100 as percentage
                CYPHER;
                $result2 = $conn_neo->run($cypher2);
                $result = $conn_neo->run($cypher);
            }else if(!isset($_POST['filter']['store'])){
                $months = [];
                foreach($_POST['filter']['month'] as $m){
                    $months[] = intval($m);
                }
                $cypher = <<< CYPHER
                    MATCH (p1:Product)-[]->(s:Sales)<-[]-(p2:Product)
                    MATCH (st:Store)-[]-(s)
                    WHERE p1.product_id = '{$_POST['filter']['product']}' AND p1 <> p2 AND s.date.month IN \$months
                    RETURN p2.product_id, COUNT(p2) as countProduct, COLLECT(s.sales_id), COLLECT(st.store_id)
                    ORDER BY countProduct DESC
                    LIMIT 5
                CYPHER;
                $cypher2 = <<< CYPHER
                    MATCH (p:Product)-[r]->(s:Sales)-[]-(st:Store)
                    WHERE p.product_id = '{$_POST['filter']['product']}'
                    AND s.date.month IN \$months
                    WITH COUNT(p) as p
                    MATCH (t:Sales)-[]-(st:Store)
                    WHERE t.date.month IN \$months
                    WITH p, COUNT(t) as t
                    RETURN toFloat(p)/toFloat(t)*100 as percentage
                CYPHER;
                $result2 = $conn_neo->run($cypher2,['months' => $months]);
                $result = $conn_neo->run($cypher,['months' => $months]);
            }else if(!isset($_POST['filter']['month'])){
                $cypher = <<< CYPHER
                    MATCH (p1:Product)-[]->(s:Sales)<-[]-(p2:Product)
                    MATCH (st:Store)-[]-(s)
                    WHERE p1.product_id = '{$_POST['filter']['product']}' AND p1 <> p2 AND st.store_id IN \$stores
                    RETURN p2.product_id, COUNT(p2) as countProduct, COLLECT(s.sales_id), COLLECT(st.store_id)
                    ORDER BY countProduct DESC
                    LIMIT 5
                CYPHER;
                $cypher2 = <<< CYPHER
                    MATCH (p:Product)-[r]->(s:Sales)-[]-(st:Store)
                    WHERE p.product_id = '{$_POST['filter']['product']}'
                    AND st.store_id IN \$stores
                    WITH COUNT(p) as p
                    MATCH (t:Sales)-[]-(st:Store)
                    WHERE st.store_id IN \$stores
                    WITH p, COUNT(t) as t
                    RETURN toFloat(p)/toFloat(t)*100 as percentage
                CYPHER;
                $result2 = $conn_neo->run($cypher2,['stores' => $_POST['filter']['store']]);
                $result = $conn_neo->run($cypher,['stores' => $_POST['filter']['store']]);
            }else{
                $months = [];
                foreach($_POST['filter']['month'] as $m){
                    $months[] = intval($m);
                }
                $cypher = <<< CYPHER
                    MATCH (p1:Product)-[]->(s:Sales)<-[]-(p2:Product)
                    MATCH (st:Store)-[]-(s)
                    WHERE p1.product_id = '{$_POST['filter']['product']}' AND p1 <> p2 AND st.store_id IN \$stores AND s.date.month IN \$months
                    RETURN p2.product_id, COUNT(p2) as countProduct, COLLECT(s.sales_id), COLLECT(st.store_id)
                    ORDER BY countProduct DESC
                    LIMIT 5
                CYPHER;
                $cypher2 = <<< CYPHER
                    MATCH (p:Product)-[r]->(s:Sales)-[]-(st:Store)
                    WHERE p.product_id = '{$_POST['filter']['product']}'
                    AND s.date.month IN \$months
                    AND st.store_id IN \$stores
                    WITH COUNT(p) as p
                    MATCH (t:Sales)-[]-(st:Store)
                    WHERE st.store_id IN \$stores
                    AND t.date.month IN \$months
                    WITH p, COUNT(t) as t
                    RETURN toFloat(p)/toFloat(t)*100 as percentage
                CYPHER;
                $result2 = $conn_neo->run($cypher2,['stores' => $_POST['filter']['store'], 'months' => $months]);
                $result = $conn_neo->run($cypher,['stores' => $_POST['filter']['store'], 'months' => $months]);
            }
            $x = [];
            $y = [];
            $z = 0;
            foreach($result as $r){
                $sql = "SELECT name FROM product WHERE product_id = :id";
                $stmt = $conn_sql->prepare($sql);
                $stmt->execute([':id' => $r->get('p2.product_id')]);
                $name = $stmt->fetchColumn();
                // $x[] = substr($name,0,15);
                $x[] = $name;
                // $x[] =  $r->get('p2.product_id');
                $y[] = $r->get('countProduct');
            }
            foreach($result2 as $r){
                $z = $r->get('percentage');
            }
            echo json_encode(['x' => $x, 'y' => $y, 'z' => $z]);
        }else if($_POST['ajax'] == 'e'){
            if($_POST['order'] == "leadTime"){
                $cypher = <<< CYPHER
                    MATCH (w:Warehouse)-[]-(d:Delivery)-[]-(s:Supplier)
                    MATCH (d)-[r]-(p:Product)
                    WHERE w.warehouse_id = '{$_POST['filter']['warehouse']}' AND p.product_id = '{$_POST['filter']['product']}'
                    WITH s.supplier_id as s, AVG(duration.inDays(d.order_date,d.arrival_date).days) as leadTime, SUM(r.unit_price * r.quantity)/SUM(r.quantity) as unit_price, p
                    RETURN s, leadTime, p.price - unit_price as margin
                    ORDER BY leadTime
                CYPHER;
            }else{
                $cypher = <<< CYPHER
                    MATCH (w:Warehouse)-[]-(d:Delivery)-[]-(s:Supplier)
                    MATCH (d)-[r]-(p:Product)
                    WHERE w.warehouse_id = '{$_POST['filter']['warehouse']}' AND p.product_id = '{$_POST['filter']['product']}'
                    WITH s.supplier_id as s, AVG(duration.inDays(d.order_date,d.arrival_date).days) as leadTime, SUM(r.unit_price * r.quantity)/SUM(r.quantity) as unit_price, p
                    RETURN s, leadTime, p.price - unit_price as margin
                    ORDER BY margin DESC
                CYPHER;
            }
            $cypher2 = <<< CYPHER
                MATCH (w:Warehouse)-[]-(d:Delivery)-[]-(s:Supplier)
                MATCH (d)-[r]-(p:Product)
                WHERE w.warehouse_id = '{$_POST['filter']['warehouse']}' AND p.product_id = '{$_POST['filter']['product']}'
                RETURN COUNT(d) as total
            CYPHER;

            $cypher3 = <<< CYPHER
                MATCH (p:Product)
                WHERE p.product_id = '{$_POST['filter']['product']}'
                RETURN p.price as price
            CYPHER;
            $result = $conn_neo->run($cypher);
            $result2 = $conn_neo->run($cypher2);
            $result3 = $conn_neo->run($cypher3);
            $x = [];
            $y = [];
            $y1 = [];
            $z = 0;
            $z1 = 0;
            foreach($result as $r){
                $sql = "SELECT name FROM supplier WHERE supplier_id = :id";
                $stmt = $conn_sql->prepare($sql);
                $stmt->execute([':id' => $r->get('s')]);
                $name = $stmt->fetchColumn();
                $x[] = $name;
                $y[] = $r->get('leadTime');
                $y1[] = $r->get('margin');
            }
            foreach($result2 as $r){
                $z = $r->get('total');
            }
            foreach($result3 as $r){
                $z1 = $r->get('price');
            }
            echo json_encode(['x' =>$x, 'y' => $y, 'y1' => $y1, 'total' => $z, 'price' => $z1]);
        }
    }
?>