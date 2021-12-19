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


if (isset($_POST['create_content'])) {

    $contentDetail = htmlspecialchars($_POST["content_detail"], ENT_QUOTES);

    $contentData = [
        ":content_publisher"=>$username,
        ":content_publisher_id"=>$sessionID,
        ":content_detail"=>$contentDetail,
    ];

    $querySendMessage1 = "INSERT INTO `contents`
    (
        `message_detail`,
        `message_getter`,
        `message_sender`,
        `delete_key`,
        `unique_name`
    )
    VALUES
    (
        :message_detail,
        :message_getter,
        :message_sender,
        :delete_key,
        :unique_name
    )";

    $pdoResult1 = $pdo->prepare($querySendMessage1);
    $pdoExecute1 = $pdoResult1->execute($messageDataForSender);

    header("Location: ../messages/conversation?with=$messageGetter");


}

?>
