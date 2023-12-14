<?php
    require_once "./connect.php";
    if(isset($_POST['upload'])){
        $upload = $_POST['upload'];
        $path_info = pathinfo($_FILES['file']['name']);
        $extension = $path_info['extension'];
        if($extension != 'csv'){
            echo json_encode(['status' => '422', 'message' => 'Only Accept .CSV file']);
            exit;
        }
        else{
            $file_tmp = $_FILES['file']['tmp_name'];
            $handle = fopen($file_tmp, 'r');
            if($upload == "supplier"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $sql = "CREATE TABLE supplier (
                        supplier_id VARCHAR(8)  PRIMARY KEY,
                        name VARCHAR(30) NOT NULL,
                        email VARCHAR(30) NOT NULL,
                        address VARCHAR(50),
                        city VARCHAR(30)
                        )";
                    $conn_sql->exec($sql);
                    $data = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        $insert = "INSERT INTO supplier (supplier_id, name, email, address, city) VALUES (:id, :name, :email, :address, :city)";
                        $stmt = $conn_sql->prepare($insert);
                        $stmt->execute([
                            ":id" => $dataIn[0],
                            ":name" => $dataIn[1],
                            ":email" => $dataIn[2],
                            ":address" => $dataIn[3],
                            ":city" => $dataIn[4]
                        ]);
                        $data[] = $dataIn;
                    }
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            } else if($upload == "product"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $sql = "CREATE TABLE product (
                        product_id VARCHAR(7)  PRIMARY KEY,
                        name VARCHAR(30) NOT NULL,
                        description TEXT NOT NULL,
                        category_id VARCHAR(7),
                        price float,
                        unit VARCHAR(20)
                        )";
                    $conn_sql->exec($sql);
                    $data = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        $insert = "INSERT INTO product (product_id, name, description, category_id, price, unit) VALUES (:id, :name, :description, :category, :price, :unit)";
                        $stmt = $conn_sql->prepare($insert);
                        $stmt->execute([
                            ":id" => $dataIn[0],
                            ":name" => $dataIn[1],
                            ":description" => $dataIn[2],
                            ":category" => $dataIn[3],
                            ":price" => $dataIn[4],
                            ":unit" => $dataIn[5]
                        ]);

                        // insert into neo4j
                        $result = $conn_neo->run(<<<CYPHER
                            MERGE (p:Products {product_id: '$dataIn[0]'})
                            CYPHER,['dbName' => 'neo4j']);
                        $data[] = $dataIn;
                    }
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }else if($upload == "category"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $sql = "CREATE TABLE category (
                        category_id VARCHAR(7) PRIMARY KEY,
                        name VARCHAR(30) NOT NULL
                        )";
                    $conn_sql->exec($sql);
                    $data = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        $insert = "INSERT INTO category (category_id, name) VALUES (:id, :name)";
                        $stmt = $conn_sql->prepare($insert);
                        $stmt->execute([
                            ":id" => $dataIn[0],
                            ":name" => $dataIn[1],
                        ]);
                        $data[] = $dataIn;
                    }
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }else if($upload == "warehouse"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $sql = "CREATE TABLE warehouse (
                        warehouse_id VARCHAR(7) PRIMARY KEY,
                        address VARCHAR(30) NOT NULL,
                        city VARCHAR(30) NOT NULL
                        )";
                    $conn_sql->exec($sql);
                    $data = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        $insert = "INSERT INTO warehouse (warehouse_id, address, city) VALUES (:id, :address, :city)";
                        $stmt = $conn_sql->prepare($insert);
                        $stmt->execute([
                            ":id" => $dataIn[0],
                            ":address" => $dataIn[1],
                            ":city" => $dataIn[2]
                        ]);
                        $data[] = $dataIn;
                    }
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }else if($upload == "store"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $sql = "CREATE TABLE store (
                        store_id VARCHAR(7) PRIMARY KEY,
                        name VARCHAR(45) NOT NULL,
                        phone VARCHAR(30) NOT NULL,
                        address VARCHAR(30) NOT NULL,
                        city VARCHAR(30) NOT NULL
                        )";
                    $conn_sql->exec($sql);
                    $data = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        $insert = "INSERT INTO store (store_id, name, phone, address, city) VALUES (:id, :name, :phone, :address, :city)";
                        $stmt = $conn_sql->prepare($insert);
                        $stmt->execute([
                            ":id" => $dataIn[0],
                            ":name" => $dataIn[1],
                            ":phone" => $dataIn[2],
                            ":address" => $dataIn[3],
                            ":city" => $dataIn[4]
                        ]);

                        // insert into neo4j
                        $result = $conn_neo->run(<<<CYPHER
                            MERGE (s:Stores {store_id: '$dataIn[0]'})
                            CYPHER,['dbName' => 'neo4j']);
                        
                        $data[] = $dataIn;
                    }
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }else if($upload == "employee"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $sql = "CREATE TABLE employee (
                        employee_id VARCHAR(7) PRIMARY KEY,
                        first_name VARCHAR(30) NOT NULL,
                        last_name VARCHAR(30) NOT NULL,
                        phone VARCHAR(30) NOT NULL,
                        email VARCHAR(30) NOT NULL,
                        hire_date DATE NOT NULL,
                        address VARCHAR(30) NOT NULL,
                        city VARCHAR(30) NOT NULL,
                        store_id VARCHAR(7) NOT NULL
                        )";
                    $conn_sql->exec($sql);
                    $data = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        $insert = "INSERT INTO employee VALUES (:id, :first,:last, :phone, :email, :hire, :address, :city, :store)";
                        $stmt = $conn_sql->prepare($insert);
                        $stmt->execute([
                            ":id" => $dataIn[0],
                            ":first" => $dataIn[1],
                            ":last" => $dataIn[2],
                            ":phone" => $dataIn[3],
                            ":email" => $dataIn[4],
                            ":hire" => DateTime::createFromFormat("dd/mm/yyyy",$dataIn[5]),
                            ":address" => $dataIn[6],
                            ":city" => $dataIn[7],
                            ":store" => $dataIn[8]
                        ]);
                        $data[] = $dataIn;
                    }
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }else if($upload == "customer"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $sql = "CREATE TABLE customer (
                        customer_id VARCHAR(8) PRIMARY KEY,
                        first_name VARCHAR(30) NOT NULL,
                        last_name VARCHAR(30) NOT NULL,
                        phone VARCHAR(30) NOT NULL,
                        email VARCHAR(30) NOT NULL,
                        gender VARCHAR(30) NOT NULL,
                        join_date DATE NOT NULL
                        )";
                    $conn_sql->exec($sql);
                    $data = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        $insert = "INSERT INTO customer  VALUES (:id, :first, :last, :phone, :email, :gender, :join)";
                        $stmt = $conn_sql->prepare($insert);
                        $stmt->execute([
                            ":id" => $dataIn[0],
                            ":first" => $dataIn[1],
                            ":last" => $dataIn[2],
                            ":phone" => $dataIn[3],
                            ":email" => $dataIn[4],
                            ":gender" => $dataIn[5],
                            ":join" => DateTime::createFromFormat("dd/mm/yyyy",$dataIn[6])
                        ]);

                        // input to neo4j
                        $result = $conn_neo->run(<<<CYPHER
                            MERGE (c:Customers {customer_id: '$dataIn[0]'})
                            CYPHER,['dbName' => 'neo4j']);
                        $data[] = $dataIn;
                    }
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }else if($upload == "delivery-to-store"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $collection = $conn_mongo->Ceje->delivery_to_store;
                    $data = [];
                    $in = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        if(!isset($in[$dataIn[0]])){
                            $in[$dataIn[0]] = [];
                        }
                        foreach($dataIn as $key => $value){
                            if($headers[$key] == "product_id"){
                                if(!isset($in[$dataIn[0]]["products"])){
                                    $in[$dataIn[0]]["products"] = [['product_id' => $value, 'qty' => $dataIn[array_search('qty', $headers)]]];
                                }else{
                                    $in[$dataIn[0]]["products"][] = ['product_id' => $value, 'qty' => $dataIn[array_search('qty', $headers)]];
                                }
                            }else if($headers[$key] == "qty"){
                                continue;
                            }else{
                                if(!isset($in[$dataIn[0]][$headers[$key]])){
                                    $in[$dataIn[0]][$headers[$key]] = $value;
                                }else{
                                    continue;
                                }
                            }
                        }
                        $data[] = $dataIn;
                    }
                    foreach($in as $key => $value){
                        $collection->insertOne($value);
                    }

                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }else if($upload == "sales"){
                // $file = $_FILES['file'];
                $collectionCust = $conn_mongo->Ceje->cust_sales;
                $collectionStore = $conn_mongo->Ceje->store_sales;
                $collectionProd = $conn_mongo->Ceje->prod_sales;
                if ($handle !== false) {
                    // Customer
                    try {
                        $sql = "SELECT * FROM customer";
                        $stmt = $conn_sql->prepare($sql);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        echo json_encode(['error' => 'Belum input Customer!', 'code' => 400]);
                        exit;
                    }
                    $resCust = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // Product
                    try {
                        $sql = "SELECT * FROM product";
                        $stmt = $conn_sql->prepare($sql);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        echo json_encode(['error' => 'Belum input Product!', 'code' => 400]);
                        exit;
                    }
                    $resProd = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Stores
                    try {
                        $sql = "SELECT * FROM store";
                        $stmt = $conn_sql->prepare($sql);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        echo json_encode(['error' => 'Belum input Store!','code' => 400]);
                        exit;
                    }
                    $resStore = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Sales
                    $headers = fgetcsv($handle, 0, ',');
                    $data = [];
                    $dataStore = [];
                    $dataCust = [];
                    $dataProd = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        // Date
                        $oriDate = $dataIn[0];
                        $dateTime = DateTime::createFromFormat('m/d/Y', $oriDate);
                        $formattedDate = $dateTime->format('Y-m-d');

                        // NEO4J
                        $cypher = <<< CYPHER
                            MERGE (c:Customer {customer_id: '$dataIn[3]'})
                            MERGE (s:Store {store_id: '$dataIn[2]'})
                            MERGE (sal:Sales {sales_id: '$dataIn[1]', date:date('$formattedDate'), rating:$dataIn[8]})
                            MERGE (p:Product {product_id: '$dataIn[5]'})
                            MERGE (c)<-[:MADE_BY]-(sal)
                            MERGE (sal)<-[r:SOLD_IN]-(p)
                            ON CREATE SET r.quantity = $dataIn[6], r.subtotal = $dataIn[7]
                            MERGE (s)<-[:PROCESSED_AT]-(sal)
                            CYPHER;
                        $res = $conn_neo->run($cypher, ['dbName' => 'neo4j']);

                        
                        // $cypher = <<< CYPHER
                        //     MATCH (s:Sales {sales_id: '$dataIn[1]'}),(p:Products {product_id:'$dataIn[5]'})
                        //     CREATE (s)<-[b:SOLD_IN]-(p)
                        //     SET b.quantity = '$dataIn[6]',b.sub_total = '$dataIn[7]'
                        //     CYPHER;
                        // $res = $conn_neo->run($cypher, ['dbName' => 'neo4j']);


                        // $cypher = <<< CYPHER
                        //     MERGE (c:Customers {customer_id: '$dataIn[3]'})-[a:TRANSACTION_IN]->(s:Sales {sales_id: '$dataIn[1]',date : '$dataIn[0]',rating : '$dataIn[8]'})-[d:HAPPENED_IN]->(st:Stores {store_id: '$dataIn[2]'})
                        //     CYPHER;
                        // $res = $conn_neo->run($cypher, ['dbName' => 'neo4j']);


                        // // MONGODB
                        // // customer base
                        // foreach($resCust as $r){
                        //     if($r['customer_id'] != $dataIn[3]) continue; 
                        //     if(!isset($dataCust[$r['customer_id']])) $dataCust[$r['customer_id']] = ['customer_id' => $r['customer_id']];
                        //     if(!isset($dataCust[$r['customer_id']]['sales'])) $dataCust[$r['customer_id']]['sales'] = [];
                        //     if(!isset($dataCust[$r['customer_id']]['sales'][$dataIn[1]])) $dataCust[$r['customer_id']]['sales'][$dataIn[1]] = ['date' => $dataIn[0] ,'total' => intval($dataIn[7])];
                        //     else $dataCust[$r['customer_id']]['sales'][$dataIn[1]]['total'] += intval($dataIn[7]);
                        //     if(!isset($dataCust[$r['customer_id']]['sales'][$dataIn[1]]['products'])) $dataCust[$r['customer_id']]['sales'][$dataIn[1]]['products'] = [['quantity' => intval($dataIn[6]), 'sub_total' => intval($dataIn[7]), 'product_id' => $dataIn[5]]];
                        //     else $dataCust[$r['customer_id']]['sales'][$dataIn[1]]['products'][] = ['quantity' => $dataIn[6], 'sub_total' => intval($dataIn[7]), 'product_id' => $dataIn[5]];
                        // }

                        // // store base
                        // foreach($resStore as $r){
                        //     if($r['store_id'] != $dataIn[2]) continue;
                        //     if(!isset($dataStore[$r['store_id']])) $dataStore[$r['store_id']] = ['store_id' => $r['store_id']];
                        //     if(!isset($dataStore[$r['store_id']]['sales'])) $dataStore[$r['store_id']]['sales'] = [];
                        //     if(!isset($dataStore[$r['store_id']]['sales'][$dataIn[1]])) $dataStore[$r['store_id']]['sales'][$dataIn[1]] = ['date' => $dataIn[0] ,'total' => intval($dataIn[7])];
                        //     else $dataStore[$r['store_id']]['sales'][$dataIn[1]]['total'] += intval($dataIn[7]);
                        //     if(!isset($dataStore[$r['store_id']]['sales'][$dataIn[1]]['products'])) $dataStore[$r['store_id']]['sales'][$dataIn[1]]['products'] = [['quantity' => intval($dataIn[6]), 'sub_total' => intval($dataIn[7]), 'product_id' => $dataIn[5]]];
                        //     else $dataStore[$r['store_id']]['sales'][$dataIn[1]]['products'][] = ['quantity' => intval($dataIn[6]), 'sub_total' => intval($dataIn[7]), 'product_id' => $dataIn[5]];
                        // }

                        // // product base
                        // foreach($resProd as $r){
                        //     if($r['product_id'] != $dataIn[5]) continue;
                        //     if(!isset($dataProd[$r['product_id']])) $dataProd[$r['product_id']] = ['product_id' => $r['product_id']];
                        //     if(!isset($dataProd[$r['product_id']]['stores'])) $dataProd[$r['product_id']]['stores'] = [];
                        //     if(!isset($dataProd[$r['product_id']]['stores'][$dataIn[2]])) $dataProd[$r['product_id']]['stores'][$dataIn[2]] = ['store_id' => $dataIn[2]];
                        //     if(!isset($dataProd[$r['product_id']]['stores'][$dataIn[2]]['sales'])) $dataProd[$r['product_id']]['stores'][$dataIn[2]]['sales'] = [];
                        //     if(!isset($dataProd[$r['product_id']]['stores'][$dataIn[2]]['sales'][$dataIn[1]])) $dataProd[$r['product_id']]['stores'][$dataIn[2]]['sales'][$dataIn[1]] = ['date' => $dataIn[0], 'quantity' => intval($dataIn[6])];
                        // }
                    }

                    // foreach ($dataCust as $key => $value){
                    //     $collectionCust->insertOne($value);
                    // }
                    // foreach ($dataStore as $key => $value){
                    //     $collectionStore->insertOne($value);
                    // }
                    // foreach ($dataProd as $key => $value){
                    //     $collectionProd->insertOne($value);
                    // }
                }else{
                    echo "hello";
                }
                echo json_encode(['cust' => $dataCust,'store' => $dataStore]);
            }else if($upload == "delivery-to-warehouse"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $collection = $conn_mongo->Ceje->delivery_to_warehouse;
                    $data = [];
                    $in = [];
                    // Process the remaining rows
                    while (($dataIn = fgetcsv($handle, 0, ',')) !== false) {
                        // Date
                        $orderDate = $dataIn[1];
                        $dateTime = DateTime::createFromFormat('m/d/Y', $orderDate);
                        $formattedOrderDate = $dateTime->format('Y-m-d');

                        $arrivalDate = $dataIn[1];
                        $dateTime = DateTime::createFromFormat('m/d/Y', $arrivalDate);
                        $formattedArrivalrDate = $dateTime->format('Y-m-d');

                        // Neo4j
                        $cypher = <<< CYPHER
                            MERGE (p:Product {product_id: '$dataIn[5]'})
                            MERGE (s:Supplier {supplier_id: '$dataIn[3]'})
                            MERGE (w:Warehouse {warehouse_id: '$dataIn[4]'})
                            MERGE (d:Delivery {delivery_id: '$dataIn[0]', order_date:date('$formattedOrderDate'), arrival_date:date('$formattedArrivalrDate')})
                            MERGE (w)<-[:DELIVERED_TO]-(p)
                            MERGE (p)-[r:DELIVERED_AT]->(d)
                            ON CREATE SET r.quantity = $dataIn[7], r.unit_price = $dataIn[6]
                            MERGE (s)<-[:PROCESSED_AT]-(d)
                            CYPHER;
                        $res = $conn_neo->run($cypher, ['dbName' => 'neo4j']);

                        // MongoDB
                        if(!isset($in[$dataIn[0]])){
                            $in[$dataIn[0]] = [];
                        }
                        foreach($dataIn as $key => $value){
                            if($headers[$key] == "product_id"){
                                if(!isset($in[$dataIn[0]]["products"])){
                                    $in[$dataIn[0]]["products"] = [['product_id' => $value, 'qty' => $dataIn[array_search('qty', $headers)], 'unit_price' => $dataIn[array_search('unit_price', $headers)]]];
                                }else{
                                    $in[$dataIn[0]]["products"][] = ['product_id' => $value, 'qty' => $dataIn[array_search('qty', $headers)], 'unit_price' => $dataIn[array_search('unit_price', $headers)]];
                                }
                            }else if($headers[$key] == "qty" || $headers[$key] == "unit_price"){
                                continue;
                            }else{
                                if(!isset($in[$dataIn[0]][$headers[$key]])){
                                    $in[$dataIn[0]][$headers[$key]] = $value;
                                }else{
                                    continue;
                                }
                            }
                        }
                        $data[] = $dataIn;
                    }
                    foreach($in as $key => $value){
                        $collection->insertOne($value);
                    }
                    
                }
                echo json_encode(['header' => $headers, 'data' => $data]);
            }
            exit;
        }
    }
?>