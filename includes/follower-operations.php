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

    $followedPersonID = htmlspecialchars($_POST["followed_id"], ENT_QUOTES);

    $queryUserDetails = $pdo->prepare("SELECT * FROM users WHERE id = '$followedPersonID'");
    $queryUserDetails->execute();

    $getUserDetails = $queryUserDetails->fetch(PDO::FETCH_ASSOC);

    $profileLock = $getUserDetails['profile_lock'];
    $profileUsername = $getUserDetails['username'];

    $followerData = [
        ":follower_id"=>$sessionID,
        ":followed_id"=>$followedPersonID
    ];

    if ($profileLock == "1") {

        $query = "INSERT INTO `follow_requests`
        (
            `request_sender`,
            `request_getter`
        )
        VALUES
        (
            :follower_id,
            :followed_id
        )";

        $pdoResult = $pdo->prepare($query);
        $pdoExecute = $pdoResult->execute($followerData);

    } else if ($profileLock == "0") {

        $query = "INSERT INTO `follower`
        (
            `follower_id`,
            `followed_id`
        )
        VALUES
        (
            :follower_id,
            :followed_id
        )";

        $pdoResult = $pdo->prepare($query);
        $pdoExecute = $pdoResult->execute($followerData);

    }

    header("Location: ../user/$profileUsername");


}


if (isset($_POST['unfollow'])) {

    $followedPersonID = htmlspecialchars($_POST["followed_id"], ENT_QUOTES);

    $queryUserDetails = $pdo->prepare("SELECT * FROM users WHERE id = '$followedPersonID'");
    $queryUserDetails->execute();

    $getUserDetails = $queryUserDetails->fetch(PDO::FETCH_ASSOC);

    $profileUsername = $getUserDetails['username'];

    $query = $pdo->prepare("DELETE FROM follower WHERE follower_id = '$sessionID' AND followed_name = '$followedPersonID'");
    $queryExecute = $query->execute();

    header("Location: ../user/$profileUsername");
}

?>
