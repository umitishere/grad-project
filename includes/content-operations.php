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
        ":content_detail"=>$contentDetail,
    ];

    $query = "INSERT INTO `contents`
    (
        `content_publisher`,
        `content_detail`
    )
    VALUES
    (
        :content_publisher,
        :content_detail
    )";

    $pdoResult = $pdo->prepare($query);
    $pdoExecute = $pdoResult->execute($contentData);

    header("Location: /graduation-project-web/anasayfa");


}

?>
