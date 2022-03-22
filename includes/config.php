<?php

// require_once("DB_CREDENTIALS.php");

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;

define('DB_SERVER', $cleardb_server);
define('DB_USERNAME', $cleardb_username);
define('DB_PASSWORD', $cleardb_password);
define('DB_NAME', $cleardb_db);

/* define('DB_SERVER', 'localhost');
define('DB_USERNAME', $DUMMY_USERNAME);
define('DB_PASSWORD', $DUMMY_PASSWORD);
define('DB_NAME', $DUMMY_DB); */

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
