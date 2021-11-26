<?php

require_once("config.php");

if (!isset($_SESSION)) {
    session_start();
}

$sessionID = $_SESSION["id"];

if (isset($_POST["update_username"])) {
    $newUsername = $_POST["new_username"];

    $query = $pdo->prepare("UPDATE users SET username = '$newUsername' WHERE id = '$sessionID'");
    $queryExec = $query->execute();

    $_SESION["username"] = $newUsername;

    header("Location: ../$newUsername");
}

?>
