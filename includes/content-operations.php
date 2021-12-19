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
        ":publisher_id"=>$sessionID,
        ":content_detail"=>$contentDetail,
    ];

    $query = "INSERT INTO `contents`
    (
        `publisher_id`,
        `content_detail`
    )
    VALUES
    (
        :publisher_id,
        :content_detail
    )";

    $pdoResult = $pdo->prepare($query);
    $pdoExecute = $pdoResult->execute($contentData);

    header("Location: /graduation-project-web/anasayfa");


}

?>
