<?php

require_once("config.php");

if (!isset($_SESSION)) {
    session_start();
}

$sessionID = $_SESSION["id"];

$queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$sessionID'");
$queryUserInfo->execute();

$getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

$username = $getUserInfo["username"];

if (isset($_POST["send_message"])) {

    $messageDetail = htmlspecialchars($_POST["message_detail"], ENT_QUOTES);
    $messageGetter = $_POST["message_getter"];

}

if (isset($_POST["delete_message"])) {



}

?>
