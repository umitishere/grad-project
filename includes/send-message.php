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

if (isset($_POST['send_post'])) {

    $messageDetail = htmlspecialchars($_POST["message_detail"], ENT_QUOTES);
    $messageGetter = htmlspecialchars($_POST["message_getter"], ENT_QUOTES);

}

if (isset($_POST["send_message"])) {

    $messageDetail = htmlspecialchars($_POST["message_detail"], ENT_QUOTES);
    $messageGetter = htmlspecialchars($_POST["message_getter"], ENT_QUOTES);

    $isThisPost = $_POST["isThisPost"];

    $queryPostInfo = $pdo->prepare("SELECT * FROM contents WHERE id = '$messageDetail'");
    $queryPostInfo->execute();

    $getPostInfo = $queryPostInfo->fetch(PDO::FETCH_ASSOC);
    $postDetail = $getPostInfo['content_detail'];

    if ($isThisPost == "1") {
        $messageDetail = "<a href='/grad-project/posts/$messageDetail' style='color: black; text-decoration: none;'>$postDetail</a>";
    }

    echo $messageDetail;

    $uniqueName = $username . rand(1, 9999999);

    $queryGetterInfo = $pdo->prepare("SELECT * FROM users WHERE username = '$messageGetter'");
    $queryGetterInfo->execute();

    $getGetterInfo = $queryGetterInfo->fetch(PDO::FETCH_ASSOC);

    $getterID = $getGetterInfo["id"];

    $messageDataForSender = [
        ":message_detail"=>$messageDetail,
        ":message_getter"=>$messageGetter,
        ":message_sender"=>$username,
        ":delete_key"=>$sessionID,
        ":unique_name"=>$uniqueName,
        ":isThisPost"=>$isThisPost
    ];

    $messageDataForGetter = [
        ":message_detail"=>$messageDetail,
        ":message_getter"=>$messageGetter,
        ":message_sender"=>$username,
        ":delete_key"=>$getterID,
        ":unique_name"=>$uniqueName,
        ":isThisPost"=>$isThisPost
    ];

    $querySendMessage1 = "INSERT INTO `messages`
    (
        `message_detail`,
        `message_getter`,
        `message_sender`,
        `delete_key`,
        `unique_name`,
        `isThisPost`
    )
    VALUES
    (
        :message_detail,
        :message_getter,
        :message_sender,
        :delete_key,
        :unique_name,
        :isThisPost
    )";

    $querySendMessage2 = "INSERT INTO `messages`
    (
        `message_detail`,
        `message_getter`,
        `message_sender`,
        `delete_key`,
        `unique_name`,
        `isThisPost`
    )
    VALUES
    (
        :message_detail,
        :message_getter,
        :message_sender,
        :delete_key,
        :unique_name,
        :isThisPost
    )";

    $pdoResult1 = $pdo->prepare($querySendMessage1);
    $pdoExecute1 = $pdoResult1->execute($messageDataForSender);

    $pdoResult2 = $pdo->prepare($querySendMessage2);
    $pdoExecute2 = $pdoResult2->execute($messageDataForGetter);

    header("Location: ../messages/conversation?with=$messageGetter");

}

if (isset($_POST["delete_message_for_me"])) {

    $messageID = $_POST["unique"];
    $conversationWith = $_POST["conversation_with"];

    $query = $pdo->prepare("DELETE FROM messages WHERE unique_name = '$messageID' AND delete_key = '$sessionID'");
    $queryExecute = $query->execute();

    header("Location: ../messages/conversation?with=$conversationWith");

}

if (isset($_POST["delete_message_for_everyone"])) {

    $messageID = $_POST["unique"];
    $conversationWith = $_POST["conversation_with"];

    $query = $pdo->prepare("DELETE FROM messages WHERE unique_name = '$messageID'");
    $queryExecute = $query->execute();

    header("Location: ../messages/conversation?with=$conversationWith");

}

?>
