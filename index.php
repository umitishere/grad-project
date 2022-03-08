<?php

$pageTitle = "Anasayfa | Grad Project";

require_once("includes/header.php");

$sqlStatementForContents = "SELECT
    contents.*,
    users.id AS user_id, users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility,
    follower.follower_id, follower.followed_id
    FROM contents
    LEFT JOIN users ON contents.publisher_id = users.id
    LEFT JOIN follower ON contents.publisher_id = follower.followed_id
    WHERE follower.follower_id = '$loggedUserID' OR contents.publisher_id = '$loggedUserID'
    ORDER BY contents.id DESC
";

$queryLastContents = $pdo->prepare($sqlStatementForContents);
$queryLastContents->execute();

$reportContentFeedbackMessage = "";

if (isset($_GET['reportContent'])) {
    $reportContentFeedbackMessage = "Şikayetiniz bize ulaşmıştır. Teşekkür ederiz.";
}

?>

<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12">

        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) { ?>

            <!-- CREATE CONTENT SECTION -->
            <section class="padding-15 content-share margin-top-15">

                <form action="/grad-project/includes/content-operations.php" method="post">

                    <textarea
                        placeholder="Ne düşünüyorsun?"
                        class="form-control"
                        name="content_detail"
                        rows="2"
                        maxlength="450"
                    ></textarea>

                    <section class="text-center margin-top-15">
                        <button type="submit" name="create_content" class="btn btn-primary btn-lg">
                            <i class="far fa-paper-plane"></i>
                        </button>
                    </section>

                </form>

            </section>
            <!-- /CREATE CONTENT SECTION -->

            <main>

                <?php

                if (isset($_GET["reportContent"])) {
                    echo "
                    <div class='margin-top-15 alert alert-success' role='alert'>
                        $reportContentFeedbackMessage
                    </div>
                    ";
                }

                ?>

                <!-- LAST CONTENTS SECTION -->
                <section>

                <?php while($getLastContents = $queryLastContents->fetch(PDO::FETCH_ASSOC)) { ?>

                    <?php

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

                    ?>

                    <!-- CONTENT CARD SECTION -->
                    <section class="margin-top-15 card padding-15">

                        <!-- CONTENT DETAILS SECTION -->
                        <section>

                            <a href="/grad-project/user/<?php echo $getLastContents['username']; ?>" class="my-links">
                                <span class="badge bg-light text-dark font-16">
                                    <img
                                        style="border-radius: 100%;"
                                        src="/grad-project/assets/img/profile_photos/<?php echo $getLastContents["profile_photo"]; ?>"
                                        width="25px" height="25px" />
                                    <?php echo $getLastContents["username"]; ?>
                                </span>
                            </a>

                            <span style="float: right; clear: right;">
                                <?php if ($getLastContents['publisher_id'] == $loggedUserID) { ?>
                                <button
                                    type="button"
                                    class="content-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editContent<?php echo $getLastContents['id']; ?>"
                                >
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <?php } else { ?>
                                    <button
                                        type="button"
                                        class="content-button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contentSettings<?php echo $getLastContents['id']; ?>"
                                    >
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                <?php } ?>
                            </span>

                            <section class="margin-top-15">
                                <a href="/grad-project/posts/<?php echo $getLastContents['id']; ?>" style="color: black; text-decoration: none;">
                                    <?php echo nl2br($getLastContents['content_detail']); ?>
                                </a>
                            </section>

                            <form action="/grad-project/includes/content-operations.php" method="post">

                                <input type="hidden" name="liked_content" value="<?php echo $getLastContents['id']; ?>" />
                                <input type="hidden" name="to_where" value="home" />

                                <!-- /CONTENT ACTION ICONS SECTION -->
                                <section class="margin-top-15 row text-center content-icons">

                                <?php

                                $likedContent = $getLastContents['id'];

                                $queryMyLikes = $pdo->prepare(
                                    "SELECT * FROM liked_contents
                                    WHERE liked_content = $likedContent
                                    AND who_liked = $loggedUserID
                                ");
                                $queryMyLikes->execute();

                                ?>

                                    <!-- LIKE BUTTON -->
                                    <div class="col-3">

                                    <?php

                                    $queryTotalLikes = $pdo->prepare(
                                        "SELECT * FROM liked_contents WHERE liked_content = $likedContent"
                                    );
                                    $queryTotalLikes->execute();

                                    $likesCount = $queryTotalLikes->rowCount();

                                    if ($queryMyLikes->rowCount() == 1) {

                                    ?>

                                        <button type="submit" name="dislike_content" class="content-button">
                                            <i class="fas fa-heart"></i>
                                            <span class="font-20"><?php ($canSeeLikes) ? (print($likesCount)) : ("") ?></span>
                                        </button>

                                    <?php } else { // WHEN USER DID NOT LIKE ?>

                                        <button type="submit" name="like_content" class="content-button">
                                            <i class="far fa-heart"></i>
                                            <span class="font-20"><?php ($canSeeLikes) ? (print($likesCount)) : ("") ?></span>
                                        </button>

                                    <?php } ?>

                                    </div>
                                    <!-- /LIKE BUTTON -->

                                    <?php

                                    $commentFromWhere = "Home";
                                    $reportFromWhere = "Home";

                                    include("modal-send-comment.php");

                                    ($getLastContents['publisher_id'] == $loggedUserID) ? include("modal-edit-content.php") : include("modal-content-settings.php")

                                    ?>

                                    <!-- COMMENT BUTTON -->
                                    <div class="col-3">

                                        <button
                                            type="button"
                                            name="like_content"
                                            class="content-button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#sendComment<?php echo $getLastContents['id']; ?>"
                                        >
                                            <i class="far fa-comments"></i>
                                        </button>

                                    </div>
                                    <!-- /COMMENT BUTTON -->

                                    <!-- FORWARD CONTENT BUTTON -->
                                    <div class="col-3">

                                        <button
                                            type="button"
                                            class="content-button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#forwardContent<?php echo $getLastContents['id']; ?>"
                                        >
                                            <i class="far fa-share-square"></i>
                                        </button>

                                        <?php

                                        $forwardFromWhere = "Home";

                                        include("modal-forward-content.php");

                                        ?>

                                    </div>
                                    <!-- /FORWARD CONTENT BUTTON -->

                                    <!-- SAVE CONTENT BUTTON -->
                                    <div class="col-3">
                                        <i class="far fa-plus-square"></i>
                                    </div>
                                    <!-- /SAVE CONTENT BUTTON -->

                                </section>
                                <!-- /CONTENT ACTION ICONS SECTION -->

                            </form>
                        </section>
                        <!-- /CONTENT DETAILS SECTION -->

                    </section>
                    <!-- /CONTENT CARD SECTION -->

                <?php } ?>

                </section>
                <!-- /LAST CONTENTS SECTION -->

        <?php } else { // Check if user logged in ?>

            <section class="text-center margin-top-15">
                <div class="alert alert-primary" role="alert">
                    Gönderileri görebilmek ve paylaşım yapabilmek için <a href="giris-yap"><b>buraya tıklayarak</b></a> giriş yapabilirsiniz.
                </div>

                <section style="margin-top: 120px;">

                </section>

            </section>

        <?php } ?>

            </main>

        </div>

        <div class="col-md-3 col-sm-12">



        </div>

    </div>
    <!-- /MAIN ROW -->

</div>
<!-- /CONTAINER -->

<?php require_once("includes/footer.php"); ?>
