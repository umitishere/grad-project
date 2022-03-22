<?php

$pageTitle = "Kaydettiğim Gönderiler | Grad Project";

require_once("includes/header.php");

$sqlStatement = "SELECT contents.*, saved_contents.*, users.id AS user_id,
    users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility
    FROM contents
    LEFT JOIN saved_contents ON contents.id = saved_contents.saved_content_id
    LEFT JOIN users ON contents.publisher_id = users.id
    WHERE saved_contents.saver_id = $loggedUserID
    ORDER BY contents.id DESC";
$queryLastContents = $pdo->prepare($sqlStatement);
$queryLastContents->execute();

$reportContentFeedbackMessage = "";

if (isset($_GET['reportContent'])) {
    $reportContentFeedbackMessage = "Şikayetiniz bize ulaşmıştır. Teşekkür ederiz.";
}

?>

<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12">

            <main>

                <section>

                    <h3 class="margin-top-15 text-center">Kaydettiğim Gönderiler</h3>
                    <hr />

                    <?php

                    if (isset($_GET["reportContent"])) {
                        echo "
                        <div class='margin-top-15 alert alert-success' role='alert'>
                            $reportContentFeedbackMessage
                        </div>
                        ";
                    }

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

                        $likedFromWhere = "Saved Contents";
                        $commentFromWhere = "Saved Contents";
                        $reportFromWhere = "Saved Contents";

                        include("content-card.php");

                    }

                    ?>

                </section>

            </main>

        </div>

        <div class="col-md-3 col-sm-12">



        </div>

    </div>

</div>

<?php require_once("includes/footer.php"); ?>
