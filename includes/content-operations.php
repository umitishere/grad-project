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
        ":content_detail"=>$contentDetail
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

    header("Location: /grad-project/anasayfa");


}

if (isset($_POST['send_comment'])) {

    $commentDetail = htmlspecialchars($_POST["comment_detail"], ENT_QUOTES);
    $commentedPost = htmlspecialchars($_POST["commented_content"], ENT_QUOTES);

    $commentData = [
        ":comment_sender"=>$sessionID,
        ":commented_post"=>$commentedPost,
        ":comment_detail"=>$commentDetail
    ];

    $query = "INSERT INTO `comments`
    (
        `comment_sender`,
        `commented_post`,
        `comment_detail`
    )
    VALUES
    (
        :comment_sender,
        :commented_post,
        :comment_detail
    )";

    $pdoResult = $pdo->prepare($query);
    $pdoExecute = $pdoResult->execute($commentData);

    header("Location: /grad-project/anasayfa");


}


if (isset($_POST['like_content'])) {

    $contentID = $_POST['liked_content'];
    $fromWhere = $_POST['to_where'];

    $queryProfileInfo = $pdo->prepare("SELECT * FROM users
        LEFT JOIN contents
        ON users.id = contents.publisher_id
        WHERE users.id = contents.publisher_id AND contents.id = '$contentID'");
    $queryProfileInfo->execute();

    $getProfileInfo = $queryProfileInfo->fetch(PDO::FETCH_ASSOC);

    $profileUsername = $getProfileInfo['username'];

    $contentData = [
        ":liked_content"=>$contentID,
        ":who_liked"=>$sessionID
    ];

    $query = "INSERT INTO `liked_contents`
    (
        `liked_content`,
        `who_liked`
    )
    VALUES
    (
        :liked_content,
        :who_liked
    )";

    $pdoResult = $pdo->prepare($query);
    $pdoExecute = $pdoResult->execute($contentData);

    if ($fromWhere == "home") {
        header("Location: /grad-project/anasayfa");
    } else if ($fromWhere == "content_detail") {
        header("Location: /grad-project/posts/$contentID");
    } else if ($fromWhere == "profile") {
        header("Location: /grad-project/user/$profileUsername");
    }

}


if (isset($_POST['dislike_content'])) {

    $contentID = $_POST['liked_content'];
    $fromWhere = $_POST['to_where'];

    $queryProfileInfo = $pdo->prepare("SELECT * FROM users
        LEFT JOIN contents
        ON users.id = contents.publisher_id
        WHERE users.id = contents.publisher_id AND contents.id = '$contentID'");
    $queryProfileInfo->execute();

    $getProfileInfo = $queryProfileInfo->fetch(PDO::FETCH_ASSOC);

    $profileUsername = $getProfileInfo['username'];

    $query = $pdo->prepare("DELETE FROM liked_contents WHERE liked_content = '$contentID' AND who_liked = '$sessionID'");
    $queryExecute = $query->execute();

    if ($fromWhere == "home") {
        header("Location: /grad-project/anasayfa");
    } else if ($fromWhere == "content_detail") {
        header("Location: /grad-project/posts/$contentID");
    } else if ($fromWhere == "profile") {
        header("Location: /grad-project/user/$profileUsername");
    }
}

if (isset($_POST['delete_content'])) {

    $contentID = $_POST['content_id'];

    $query = $pdo->prepare("DELETE FROM contents WHERE id = '$contentID'");
    $queryExecute = $query->execute();

    header("Location: /grad-project/anasayfa");
}

?>
