<?php

$pageTitle = "Anasayfa | Grad Project";

require_once("includes/header.php");

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    $queryContentPreferences = $pdo->prepare("SELECT * FROM users WHERE id = '$loggedUserID'");
    $queryContentPreferences->execute();

    $getContentPreferences = $queryContentPreferences->fetch(PDO::FETCH_ASSOC);

    $contentPreference = $getContentPreferences["content_preference"];
    $myUniversity = $getContentPreferences["university"];

    $queryCheckIfUserMuted = $pdo->prepare("SELECT * FROM muted_users WHERE muter_id = '$loggedUserID' AND muted_user_id = ''");
    $queryCheckIfUserMuted->execute();

    $sqlStatementForContents = "";

    if ($contentPreference == "Sadece Takip Ettiklerim") {

        $sqlStatementForContents = "SELECT
            contents.*,
            users.id AS user_id, users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility,
            follower.follower_id, follower.followed_id,
            muted_users.muted_user_id, muted_users.muter_id
            FROM contents
            LEFT JOIN users ON contents.publisher_id = users.id
            LEFT JOIN follower ON contents.publisher_id = follower.followed_id
            LEFT JOIN muted_users ON contents.publisher_id = muted_users.muted_user_id
            WHERE (
                IF (
                    muted_users.muted_user_id = contents.publisher_id,
                    (
                        (follower.follower_id = '$loggedUserID' AND follower.followed_id = contents.publisher_id)
                        OR contents.publisher_id = '$loggedUserID'
                    )
                    AND contents.publisher_id != muted_users.muted_user_id,
                    (follower.follower_id = '$loggedUserID' AND follower.followed_id = contents.publisher_id) OR contents.publisher_id = '$loggedUserID'
                )
            )
            GROUP BY contents.content_detail
            ORDER BY contents.id DESC
        ";

    } else if ($contentPreference == "Sadece Üniversitemdekiler") {

        $sqlStatementForContents = "SELECT
            contents.*,
            users.id AS user_id, users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility,
            muted_users.muted_user_id, muted_users.muter_id
            FROM contents
            LEFT JOIN users ON contents.publisher_id = users.id
            LEFT JOIN muted_users ON contents.publisher_id = muted_users.muted_user_id
            WHERE (
                IF (
                    muted_users.muted_user_id = contents.publisher_id,
                    users.university = '$myUniversity' AND users.profile_lock = '0' AND contents.publisher_id != muted_users.muted_user_id,
                    users.university = '$myUniversity' AND users.profile_lock = '0'
                )
            )
            GROUP BY contents.content_detail
            ORDER BY contents.id DESC
        ";

    } else if ($contentPreference == "Açık Olan Tüm Gönderiler") {

        $sqlStatementForContents = "SELECT
            contents.*,
            users.id AS user_id, users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility,
            muted_users.muted_user_id, muted_users.muter_id
            FROM contents
            LEFT JOIN users ON contents.publisher_id = users.id
            LEFT JOIN muted_users ON contents.publisher_id = muted_users.muted_user_id
            WHERE (
                IF (
                    muted_users.muted_user_id = contents.publisher_id,
                    users.profile_lock = '0' AND contents.publisher_id != muted_users.muted_user_id,
                    users.profile_lock = '0'
                )
            )
            GROUP BY contents.content_detail
            ORDER BY contents.id DESC
        ";

    }

    $queryLastContents = $pdo->prepare($sqlStatementForContents);
    $queryLastContents->execute();

} else {

    // USER NOT LOGGED IN

    $sqlStatementForContents = "SELECT
        contents.*,
        users.id AS user_id, users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility
        FROM contents
        LEFT JOIN users ON contents.publisher_id = users.id
        WHERE users.profile_lock = '0'
        GROUP BY contents.content_detail
        ORDER BY contents.id DESC
    ";

    $queryLastContents = $pdo->prepare($sqlStatementForContents);
    $queryLastContents->execute();

}

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

                <form action="<?php echo $rootPath; ?>/includes/content-operations.php" method="post">

                    <textarea
                        placeholder="Ne düşünüyorsun?"
                        class="form-control"
                        name="content_detail"
                        rows="2"
                        maxlength="450"
                    ></textarea>

                    <section class="text-center margin-top-15">
                        <button type="submit" name="create_content" class="btn btn-primary">
                            <i class="far fa-paper-plane"></i>
                        </button>
                    </section>

                </form>

            </section>
            <!-- /CREATE CONTENT SECTION -->

            <!-- CONTENT PREFERENCES -->
            <section class="text-center">
                <button class='btn btn-outline-primary margin-top-15' data-bs-toggle='modal' data-bs-target='#contentPreferences'>
                    <i class="fas fa-cog"></i> Gönderi Tercihleri
                </button>
            </section>
            <!-- /CONTENT PREFERENCES -->

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

        <?php } ?>

                <?php

                while($getLastContents = $queryLastContents->fetch(PDO::FETCH_ASSOC)) {

                    $contentID = $getLastContents['id'];

                    // This is because of using these inside of while loop
                    $getLikesName = "getLikes" . $contentID;
                    $getTotalLikesName = "getTotalLikes" . $contentID;

                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

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

                    }

                    include("content-card.php");

                }

                ?>

                </section>
                <!-- /LAST CONTENTS SECTION -->

            </main>

        </div>

        <div class="col-md-3 col-sm-12">



        </div>

    </div>
    <!-- /MAIN ROW -->

</div>
<!-- /CONTAINER -->

<?php

require_once("modal-content-preferences.php");
require_once("includes/footer.php");

?>
