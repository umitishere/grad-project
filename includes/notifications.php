<?php

require_once("config.php");

if (!isset($_SESSION)) {
    session_start();
}

$sessionID = $_SESSION["id"];

if (isset($_POST['mark_as_read'])) {

    $query = $pdo->prepare("UPDATE notifications
        SET isRead = '1'
        WHERE notification_getter_id = '$sessionID' AND isRead = '0'");
    $queryExeute = $query->execute();

    header("Location: ../anasayfa");

}

?>
