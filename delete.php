<?php
    require_once './connect.php';
    if(isset($_POST['db']) && isset($_POST['delete'])){
        if($_POST['db'] == "sql"){
            $sql = "DROP TABLE ".$_POST['delete'];
            $conn_sql->exec($sql);
            exit;
        }
    }
?>