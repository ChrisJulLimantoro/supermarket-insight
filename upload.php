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
                $file = $_FILES['file'];
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
            }
            exit;
        }
    }
?>