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
            }else if($upload == "delivery-to-warehouse"){
                // $file = $_FILES['file'];
                if ($handle !== false) {
                    $headers = fgetcsv($handle, 0, ',');
                    $collection = $conn_mongo->Ceje->delivery_to_warehouse;
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