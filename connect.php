<?php
    require_once 'autoload.php';
    // connection to MySql
    $conn_sql = new PDO("mysql:host=localhost;dbname=supermarket_insight", 'root', '');

    // Connection to MonggoDB
    $conn_mongo = new MongoDB\Client();

    // Connection to Neo4j
    $conn_neo = Laudis\Neo4j\ClientBuilder::create()
        ->withDriver('default', 'neo4j://neo4j:password@localhost')
        ->build();
?>