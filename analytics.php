<?php
    require_once "connect.php";
?>
<?php
    if(isset($_POST['ajax'])){
        if($_POST['ajax'] == 'a'){

        }
        else if($_POST['ajax'] == 'c'){
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
                            'totalSales' => ['$sum' => '$sales.v.total']
                        ]
                    ],
                    [
                        '$sort' => ['totalSales' => -1]
                    ],
                    [
                        '$limit' => 5
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
                                            $_POST['filter']
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        '$addFields' => [
                            'totalSales' => ['$sum' => '$monthFilteredSales.v.total']
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => '$store_id',
                            'totalSales' => ['$sum' => '$totalSales']
                        ]
                    ],
                    [
                        '$sort' => ['totalSales' => -1]
                    ],
                    [
                        '$limit' => 5
                    ]
                ];
            }
            
            // Execute aggregation pipeline
            $result = $collectionStore->aggregate($pipeline);
            $x = [];
            $y = [];
            foreach ($result as $r){
                $mysql = "SELECT name FROM store WHERE store_id = :id";

                $x[] = $r['_id'];
                $y[] = $r['totalSales'];
            }
            echo json_encode(['x' => $x, 'y' => $y]);
            exit;
        }
    }
?>