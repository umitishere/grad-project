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
    $messageGetter = htmlspecialchars($_POST["message_getter"], ENT_QUOTES);

    $queryGetterInfo = $pdo->prepare("SELECT * FROM users WHERE username = '$messageGetter'");
    $queryGetterInfo->execute();
    
    $getGetterInfo = $queryGetterInfo->fetch(PDO::FETCH_ASSOC);
    
    $getterID = $getGetterInfo["id"];

    $messageDataForSender = [
        ":message_detail"=>$messageDetail,
        ":message_getter"=>$messageGetter,
        ":message_sender"=>$username,
        ":delete_key"=>$sessionID
    ];

    $messageDataForGetter = [
        ":message_detail"=>$messageDetail,
        ":message_getter"=>$messageGetter,
        ":message_sender"=>$username,
        ":delete_key"=>$getterID
    ];

    $querySendMessage1 = "INSERT INTO `messages`
    (
        `message_detail`,
        `message_getter`,
        `message_sender`,
        `delete_key`
    )
    VALUES 
    (
        :message_detail,
        :message_getter,
        :message_sender,
        :delete_key
    )";

    $querySendMessage2 = "INSERT INTO `messages`
    (
        `message_detail`,
        `message_getter`,
        `message_sender`,
        `delete_key`
    )
    VALUES 
    (
        :message_detail,
        :message_getter,
        :message_sender,
        :delete_key
    )";

    $pdoResult1 = $pdo->prepare($querySendMessage1);
    $pdoExecute1 = $pdoResult1->execute($messageDataForSender);

    $pdoResult2 = $pdo->prepare($querySendMessage2);
    $pdoExecute2 = $pdoResult2->execute($messageDataForGetter);

    header("Location: ../messages/conversation?with=$messageGetter");

}

if (isset($_POST["delete_message_for_me"])) {

    $messageID = $_POST["message_id"];
    $conversationWith = $_POST["conversation_with"];

    $query = $pdo->prepare("DELETE FROM messages WHERE id = '$messageID' AND delete_key = '$sessionID'");
    $queryExecute = $query->execute();

    header("Location: ../messages/conversation?with=$conversationWith");

}

?>
