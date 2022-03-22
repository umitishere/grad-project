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
    WHERE (follower.follower_id = '$loggedUserID' AND follower.followed_id = contents.publisher_id) OR contents.publisher_id = '$loggedUserID'
    GROUP BY contents.content_detail
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

                <form action="/includes/content-operations.php" method="post">

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

                <?php

                while($getLastContents = $queryLastContents->fetch(PDO::FETCH_ASSOC)) {

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

                    $likedFromWhere = "Home";
                    $commentFromWhere = "Home";
                    $reportFromWhere = "Home";

                    include("content-card.php");

                }

                ?>

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
