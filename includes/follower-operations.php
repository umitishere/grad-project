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

    // SEND NOTIFICATION

    $notificationDetail = "<a style='text-decoration: none;' href='/user/$username'><b>$username</b></a> seni takip etmeye başladı.";

    $notificationData = [
        ":notification_detail"=>$notificationDetail,
        ":notification_getter_id"=>$followedPersonID
    ];

    $queryNotification = "INSERT INTO `notifications`
    (
        `notification_detail`,
        `notification_getter_id`
    )
    VALUES
    (
        :notification_detail,
        :notification_getter_id
    )";

    $pdoResultNotification = $pdo->prepare($queryNotification);
    $pdoExecuteNotification = $pdoResultNotification->execute($notificationData);

    // /SEND NOTIFICATION


    header("Location: ../user/$profileUsername");


}

if (isset($_POST['unfollow'])) {

    $followedPersonID = htmlspecialchars($_POST["followed_id"], ENT_QUOTES);

    $queryUserDetails = $pdo->prepare("SELECT * FROM users WHERE id = '$followedPersonID'");
    $queryUserDetails->execute();

    $getUserDetails = $queryUserDetails->fetch(PDO::FETCH_ASSOC);

    $profileUsername = $getUserDetails['username'];

    $query = $pdo->prepare("DELETE FROM follower WHERE follower_id = '$sessionID' AND followed_id = '$followedPersonID'");
    $queryExecute = $query->execute();

    header("Location: ../user/$profileUsername");
}


if (isset($_POST['accept_follow_request'])) {

    $requestSender = htmlspecialchars($_POST["request_sender"], ENT_QUOTES);

    $followerData = [
        ":follower_id"=>$requestSender,
        ":followed_id"=>$sessionID
    ];

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

    // DELETE FOLLOW REQUEST AFTER ACCEPTING
    $queryDeleteRequest = $pdo->prepare("DELETE FROM follow_requests WHERE request_sender = '$requestSender' AND request_getter = '$sessionID'");
    $queryExecute2 = $queryDeleteRequest->execute();

    header("Location: ../user/$username");

}


if (isset($_POST['decline_follow_request'])) {

    $requestSender = htmlspecialchars($_POST["request_sender"], ENT_QUOTES);

    $query = $pdo->prepare("DELETE FROM follow_requests WHERE request_sender = '$requestSender' AND request_getter = '$sessionID'");
    $queryExecute = $query->execute();

    header("Location: ../user/$username");

}

?>
