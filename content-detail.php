<?php

$pageTitle = "Gönderi Detayı | Grad Project";

if (!isset($_SESSION)) {
    session_start();
}

$contentID = "";

if (isset($_GET)) {
    $contentID = $_GET['contentID'];
}

$reportContentFeedbackMessage = "";

if (isset($_GET['reportContent'])) {
    $reportContentFeedbackMessage = "Şikayetiniz bize ulaşmıştır. Teşekkür ederiz.";
}

require_once("includes/header.php");


$sqlStatementLastContents = "SELECT contents.*, users.id AS user_id,
    users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility
    FROM contents
    LEFT JOIN users ON contents.publisher_id = users.id
    WHERE contents.id = '$contentID'
    ORDER BY contents.id DESC";
$queryLastContents = $pdo->prepare($sqlStatementLastContents);
$queryLastContents->execute();

if ($queryLastContents->rowCount() == 0) {

    echo "<br /><br /><h3 class='text-center'>Aradığınız gönderi bulunamadı.</h3>";
    exit;

} else {

    $getLastContents = $queryLastContents->fetch(PDO::FETCH_ASSOC);

}

$whoPostedThis = $getLastContents["publisher_id"];

$queryPosterInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$whoPostedThis'");
$queryPosterInfo->execute();

$getPosterInfo = $queryPosterInfo->fetch(PDO::FETCH_ASSOC);

$publisherID = $getPosterInfo['id'];
$postUsername = $getPosterInfo['username'];

$queryLikesName = "queryLikes" . $contentID;
$getLikesName = "getLikes" . $contentID;

$queryTotalLikesName = "queryTotalLikes" . $contentID;
$getTotalLikesName = "getTotalLikes" . $contentID;

$queryFollowName = "queryFollow" . $contentID;
$getFollowName = "getFollow" . $contentID;

$queryFollowName = $pdo->prepare("SELECT * FROM follower WHERE follower_id = '$loggedUserID' AND followed_id = '$whoPostedThis'");
$queryFollowName->execute();

$canSeePost = true;
$canSeeLikes = true;
$canSeeComments = true;

if ($getPosterInfo['profile_lock'] == 1 && $getPosterInfo['username'] != $loggedUsername) {
    if ($queryFollowName->rowCount() == 1) {
        $canSeePost = true;
    } else {
        $canSeePost = false;
    }
}

if ($getPosterInfo['like_visibility'] == 0 && $getPosterInfo['username'] != $loggedUsername) {
    $canSeeLikes = false;
} else {
    $canSeeLikes = true;
}

if ($getPosterInfo['comment_visibility'] == 0 && $getPosterInfo['username'] != $loggedUsername) {
    $canSeeComments = false;
} else {
    $canSeeComments = true;
}

?>

<section class="container">

    <?php

    if (isset($_GET["reportContent"])) {
        echo "
        <div class='margin-top-15 alert alert-success' role='alert'>
            $reportContentFeedbackMessage
        </div>
        ";
    }

    $contentID = $getLastContents['id'];

    // This is because of using these inside of while loop
    $getLikesName = "getLikes" . $contentID;
    $getTotalLikesName = "getTotalLikes" . $contentID;

    $canSeeLikes = true;
    $canSeeComments = true;

    if ($getLastContents['like_visibility'] == 0 && $getLastContents['user_id'] != $loggedUserID) {
        $canSeeLikes = false;
    } else {
        $canSeeLikes = true;
    }

    if ($getLastContents['comment_visibility'] == 0 && $getLastContents['user_id'] != $loggedUserID) {
        $canSeeComments = false;
    } else {
        $canSeeComments = true;
    }

    $likedFromWhere = "Content Detail";
    $commentFromWhere = "Content Detail";
    $reportFromWhere = "Content Detail";

    include("content-card.php");

    ?>

</section>

<?php require_once("includes/footer.php"); ?>
