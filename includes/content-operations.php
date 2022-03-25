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

if (isset($_POST['report_content'])) {

    $contentID = htmlspecialchars($_POST["content_id"], ENT_QUOTES);
    $fromWhere = htmlspecialchars($_POST["from_where"], ENT_QUOTES);

    $reportData = [
        ":reporter_id"=>$sessionID,
        ":reported_content_id"=>$contentID
    ];

    $query = "INSERT INTO `content_reports`
    (
        `reporter_id`,
        `reported_content_id`
    )
    VALUES
    (
        :reporter_id,
        :reported_content_id
    )";

    $reportResult = $pdo->prepare($query);
    $reportExecute = $reportResult->execute($reportData);

    if ($fromWhere == "Home") {
        header("Location: ../anasayfa?reportContent=success");
    } else if ($fromWhere == "Content Detail") {
        header("Location: ../posts/$contentID?reportContent=success");
    } else if ($fromWhere == "Profile Page") {

        $profileUsername = htmlspecialchars($_POST['profile_username'], ENT_QUOTES);

        header("Location: ../user/$profileUsername?reportContent=success");
    } else if ($fromWhere == "Liked Contents") {
        header("Location: ../begendigim-gonderiler?reportContent=success");
    }

}

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

    header("Location: ../anasayfa");


}

if (isset($_POST['send_comment'])) {

    $commentDetail = htmlspecialchars($_POST["comment_detail"], ENT_QUOTES);
    $commentedPost = htmlspecialchars($_POST["commented_content"], ENT_QUOTES);

    $fromWhere = $_POST['liked_from_where'];

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

    header("Location: ../posts/$commentedPost");

}


if (isset($_POST['like_content'])) {

    $contentID = $_POST['liked_content'];
    $fromWhere = $_POST['liked_from_where'];

    $queryProfileInfo = $pdo->prepare("SELECT * FROM users
        LEFT JOIN contents
        ON users.id = contents.publisher_id
        WHERE users.id = contents.publisher_id AND contents.id = '$contentID'");
    $queryProfileInfo->execute();

    $getProfileInfo = $queryProfileInfo->fetch(PDO::FETCH_ASSOC);

    $profileUsername = $getProfileInfo['username'];
    $profileID = $getProfileInfo['publisher_id'];

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

    // SEND NOTIFICATION

    $notificationDetail = "
        <a style='text-decoration: none;' href='../user/$username'>
            <b>$username</b>
        </a> <a style='text-decoration: none; color: white;' href='../posts/$contentID'><b>gönderini beğendi.</b></a>";

    $notificationData = [
        ":notification_detail"=>$notificationDetail,
        ":notification_getter_id"=>$profileID
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

    if ($fromWhere == "Home") {
        header("Location: ../anasayfa");
    } else if ($fromWhere == "Content Detail") {
        header("Location: ../posts/$contentID");
    } else if ($fromWhere == "Profile Page") {
        header("Location: ../user/$profileUsername");
    } else if ($fromWhere == "Liked Contents") {
        header("Location: ../begendigim-gonderiler");
    }

}


if (isset($_POST['dislike_content'])) {

    $contentID = $_POST['liked_content'];
    $fromWhere = $_POST['liked_from_where'];

    $queryProfileInfo = $pdo->prepare("SELECT * FROM users
        LEFT JOIN contents
        ON users.id = contents.publisher_id
        WHERE users.id = contents.publisher_id AND contents.id = '$contentID'");
    $queryProfileInfo->execute();

    $getProfileInfo = $queryProfileInfo->fetch(PDO::FETCH_ASSOC);

    $profileUsername = $getProfileInfo['username'];

    $query = $pdo->prepare("DELETE FROM liked_contents WHERE liked_content = '$contentID' AND who_liked = '$sessionID'");
    $queryExecute = $query->execute();

    if ($fromWhere == "Home") {
        header("Location: ../anasayfa");
    } else if ($fromWhere == "Content Detail") {
        header("Location: ../posts/$contentID");
    } else if ($fromWhere == "Profile Page") {
        header("Location: ../user/$profileUsername");
    } else if ($fromWhere == "Liked Contents") {
        header("Location: ../begendigim-gonderiler");
    }
}

if (isset($_POST['delete_content'])) {

    $contentID = $_POST['content_id'];

    $query = $pdo->prepare("DELETE FROM contents WHERE id = '$contentID'");
    $queryExecute = $query->execute();

    header("Location: ../anasayfa");
}



if (isset($_POST['save_to_new_list'])) {

    $contentID = htmlspecialchars($_POST["saved_content_id"], ENT_QUOTES);
    $listName = htmlspecialchars($_POST["list_name"], ENT_QUOTES);
    $fromWhere = htmlspecialchars($_POST["from_where"], ENT_QUOTES);
    $profileUsername = htmlspecialchars($_POST["profile_username"], ENT_QUOTES);

    $saveContentData = [
        ":saved_content_id"=>$contentID,
        ":list_name"=>$listName,
        ":saver_id"=>$sessionID
    ];

    $query = "INSERT INTO `saved_contents`
    (
        `saved_content_id`,
        `list_name`,
        `saver_id`
    )
    VALUES
    (
        :saved_content_id,
        :list_name,
        :saver_id
    )";

    $saveResult = $pdo->prepare($query);
    $saveExecute = $saveResult->execute($saveContentData);

    if ($fromWhere == "Home") {
        header("Location: ../anasayfa");
    } else if ($fromWhere == "Content Detail") {
        header("Location: ../posts/$contentID");
    } else if ($fromWhere == "Profile Page") {

        $profileUsername = htmlspecialchars($_POST['profile_username'], ENT_QUOTES);

        header("Location: ../user/$profileUsername");
    } else if ($fromWhere == "Liked Contents") {
        header("Location: ../begendigim-gonderiler");
    }

}


if (isset($_POST['save_to_existing_list'])) {

    $contentID = htmlspecialchars($_POST["saved_content_id"], ENT_QUOTES);
    $listName = htmlspecialchars($_POST["list_name"], ENT_QUOTES);
    $fromWhere = htmlspecialchars($_POST["from_where"], ENT_QUOTES);
    $profileUsername = htmlspecialchars($_POST["profile_username"], ENT_QUOTES);

    $saveContentData = [
        ":saved_content_id"=>$contentID,
        ":list_name"=>$listName,
        ":saver_id"=>$sessionID
    ];

    $query = "INSERT INTO `saved_contents`
    (
        `saved_content_id`,
        `list_name`,
        `saver_id`
    )
    VALUES
    (
        :saved_content_id,
        :list_name,
        :saver_id
    )";

    $saveResult = $pdo->prepare($query);
    $saveExecute = $saveResult->execute($saveContentData);

    if ($fromWhere == "Home") {
        header("Location: ../anasayfa");
    } else if ($fromWhere == "Content Detail") {
        header("Location: ../posts/$contentID");
    } else if ($fromWhere == "Profile Page") {

        $profileUsername = htmlspecialchars($_POST['profile_username'], ENT_QUOTES);

        header("Location: ../user/$profileUsername");
    } else if ($fromWhere == "Liked Contents") {
        header("Location: ../begendigim-gonderiler");
    }

}



if (isset($_POST['remove_from_saved_contents'])) {

    $contentID = htmlspecialchars($_POST["liked_content"], ENT_QUOTES);
    $fromWhere = htmlspecialchars($_POST["liked_from_where"], ENT_QUOTES);
    $profileUsername = htmlspecialchars($_POST["profile_username"], ENT_QUOTES);

    $query = $pdo->prepare("DELETE FROM saved_contents WHERE saved_content_id = '$contentID' AND saver_id = '$sessionID'");
    $queryExecute = $query->execute();

    if ($fromWhere == "Home") {
        header("Location: ../anasayfa");
    } else if ($fromWhere == "Content Detail") {
        header("Location: ../posts/$contentID");
    } else if ($fromWhere == "Profile Page") {

        $profileUsername = htmlspecialchars($_POST['profile_username'], ENT_QUOTES);

        header("Location: ../user/$profileUsername");
    } else if ($fromWhere == "Liked Contents") {
        header("Location: ../begendigim-gonderiler");
    } else if ($fromWhere = "Saved Contents") {
        header("Location: ../kaydettigim-gonderiler");
    }

}


if (isset($_POST["mute_user"])) {

    $contentID = htmlspecialchars($_POST["content_id"], ENT_QUOTES);
    $fromWhere = htmlspecialchars($_POST["from_where"], ENT_QUOTES);

    $queryMutedUserInfo = $pdo->prepare("SELECT * FROM contents WHERE id = $contentID");
    $queryMutedUserInfo->execute();

    $getMutedUserInfo = $queryMutedUserInfo->fetch(PDO::FETCH_ASSOC);

    $mutedUserID = $getMutedUserInfo['publisher_id'];

    $mutedUserData = [
        ":muted_user_id"=>$mutedUserID,
        ":muter_id"=>$sessionID
    ];

    $query = "INSERT INTO `muted_users`
    (
        `muted_user_id`,
        `muter_id`
    )
    VALUES
    (
        :muted_user_id,
        :muter_id
    )";

    $muteResult = $pdo->prepare($query);
    $muteExecute = $muteResult->execute($mutedUserData);

    if ($fromWhere == "Home") {
        header("Location: ../anasayfa");
    } else if ($fromWhere == "Content Detail") {
        header("Location: ../posts/$contentID");
    } else if ($fromWhere == "Profile Page") {

        $profileUsername = htmlspecialchars($_POST['profile_username'], ENT_QUOTES);

        header("Location: ../user/$profileUsername");
    } else if ($fromWhere == "Liked Contents") {
        header("Location: ../begendigim-gonderiler");
    }

}


?>
