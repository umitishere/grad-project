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


if (isset($_POST['follow'])) {

    $followedPerson = htmlspecialchars($_POST["followed_person"], ENT_QUOTES);

    $followerData = [
        ":follower_name"=>$username,
        ":followed_name"=>$followedPerson
    ];

    $query = "INSERT INTO `follower`
    (
        `follower_name`,
        `followed_name`
    )
    VALUES
    (
        :follower_name,
        :followed_name
    )";

    $pdoResult = $pdo->prepare($query);
    $pdoExecute = $pdoResult->execute($followerData);

    header("Location: ../user/$followedPerson");


}


if (isset($_POST['unfollow'])) {

    $followedPerson = htmlspecialchars($_POST["followed_person"], ENT_QUOTES);

    $query = $pdo->prepare("DELETE FROM follower WHERE follower_name = '$username' AND followed_name = '$followedPerson'");
    $queryExecute = $query->execute();

    header("Location: ../user/$followedPerson");
}

?>
