<?php

$rootPath = "";

if ($_SERVER["SERVER_NAME"] == "localhost") {
    $rootPath = "/grad-project/";
} else {
    $rootPath = "";
}

require_once("DB_CREDENTIALS.php");

define('DB_SERVER', 'localhost');
define('DB_USERNAME', $DUMMY_USERNAME);
define('DB_PASSWORD', $DUMMY_PASSWORD);
define('DB_NAME', $DUMMY_DB);

/* Attempt to connect to MySQL database */
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query("SET CHARACTER SET UTF8");
} catch(PDOException $e){
    die("HATA: Veritabanına bağlanılamadı. " . $e->getMessage());
}
?>
