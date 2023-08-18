<?php

function dbConnect() {
    $servername = $_ENV['MYSQL_SERVER'];
    $username = $_ENV['MYSQL_USER'];
    $password = $_ENV['MYSQL_PASS'];
    $dbname = $_ENV['MYSQL_DB'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
