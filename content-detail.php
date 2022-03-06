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

$queryContentDetail = $pdo->prepare("SELECT * FROM contents WHERE id = '$contentID'");
$queryContentDetail->execute();

if ($queryContentDetail->rowCount() == 0) {

    echo "<br /><br /><h3 class='text-center'>Aradığınız gönderi bulunamadı.</h3>";
    exit;

} else {

    $getContentDetail = $queryContentDetail->fetch(PDO::FETCH_ASSOC);

}

$whoPostedThis = $getContentDetail["publisher_id"];

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

    ?>

    <section class="margin-top-15 card padding-15">

        <section>
            <a href="/grad-project/user/<?php echo $getPosterInfo['username']; ?>" class="my-links">
                <span class="badge bg-light text-dark font-16">
                    <img
                        style="border-radius: 100%;"
                        src="/grad-project/assets/img/profile_photos/<?php echo $getPosterInfo["profile_photo"]; ?>"
                        width="25px" height="25px" />
                    <?php echo $getPosterInfo["username"]; ?>
                </span>
            </a>
            <span style="float: right; clear: right;">
                <?php if ($getContentDetail['publisher_id'] == $loggedUserID) { ?>
                <button
                    type="button"
                    class="content-button"
                    data-bs-toggle="modal"
                    data-bs-target="#editContent<?php echo $getContentDetail['id']; ?>"
                >
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <?php } else { ?>
                    <button
                        type="button"
                        class="content-button"
                        data-bs-toggle="modal"
                        data-bs-target="#contentSettings<?php echo $getContentDetail['id']; ?>"
                    >
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                <?php } ?>
            </span>
            <section class="margin-top-15">
                <?php echo nl2br($getContentDetail['content_detail']); ?>
            </section>

            <form action="/grad-project/includes/content-operations.php" method="post">

                <input type="hidden" name="liked_content" value="<?php echo $getContentDetail['id']; ?>" />
                <input type="hidden" name="to_where" value="content_detail" />

                <section class="margin-top-15 row text-center content-icons">

                    <?php

                        $likedContent = $getContentDetail['id'];

                        $queryLikesName = $pdo->prepare(
                            "SELECT * FROM liked_contents
                            WHERE liked_content = $likedContent
                            AND who_liked = $loggedUserID
                        ");
                        $queryLikesName->execute();

                    ?>

                    <div class="col-3">

                        <?php

                            $queryTotalLikesName = $pdo->prepare(
                                "SELECT * FROM liked_contents WHERE liked_content = $likedContent"
                            );
                            $queryTotalLikesName->execute();

                            $likesCount = $queryTotalLikesName->rowCount();

                        ?>

                        <?php if ($queryLikesName->rowCount() == 1) { ?>

                        <button type="submit" name="dislike_content" class="content-button">
                            <i class="fas fa-heart"></i>
                            <span class="font-20">
                                <?php echo $likesCount; ?>
                            </span>
                        </button>

                        <?php } else { ?>

                        <button type="submit" name="like_content" class="content-button">
                            <i class="far fa-heart"></i>
                            <span class="font-20">
                                <?php echo $likesCount; ?>
                            </span>
                        </button>

                        <?php } ?>

                    </div>

                    <?php
                        $commentFromWhere = "Content Detail";
                        $reportFromWhere = "Content Detail";

                        include("modal-send-comment.php");

                        ($getContentDetail['publisher_id'] == $loggedUserID) ? include("modal-edit-content.php") : include("modal-content-settings.php")
                    ?>

                    <div class="col-3">
                        <button
                            type="button"
                            name="like_content"
                            class="content-button"
                            data-bs-toggle="modal"
                            data-bs-target="#sendComment<?php echo $getContentDetail['id']; ?>"
                        >
                            <i class="far fa-comments"></i>
                        </button>
                    </div>

                    <div class="col-3">
                        <button
                            type="button"
                            class="content-button"
                            data-bs-toggle="modal"
                            data-bs-target="#forwardContent<?php echo $getContentDetail['id']; ?>"
                        >
                            <i class="far fa-share-square"></i>
                        </button>

                        <?php $forwardFromWhere = "Content Detail"; ?>
                        <?php include("modal-forward-content.php"); ?>
                    </div>

                    <div class="col-3">
                        <i class="far fa-plus-square"></i>
                    </div>

                </section>

            </form>
        </section>

    </section>

</section>

<?php require_once("includes/footer.php"); ?>
