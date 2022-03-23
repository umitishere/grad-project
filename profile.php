<?php

if (!isset($_SESSION)) {
    session_start();
}

$myProfile = false;
$profileUsername = $_GET["username"];
$errorMessage = "";
$myUsername = $_SESSION["username"];
$profileID = "";

$userIsBlocked = "";
$userBlockedMe = "";


$fromWhere = "Profile Page";

$pageTitle = $profileUsername . " profili | Grad Project";

require_once("includes/header.php");

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: $rootPath/giris-yap");
    exit;
}

$profileInfo = $pdo->prepare("SELECT * FROM users WHERE username = '$profileUsername'");
$profileInfo->execute();

$getProfileInfo = $profileInfo->fetch(PDO::FETCH_ASSOC);

$chekIfUserExists = "SELECT id FROM users WHERE username = :username";

if($stmt = $pdo->prepare($chekIfUserExists)) {

    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
    $param_username = trim($profileUsername);

    if($stmt->execute()){
        if ($stmt->rowCount() == 1) {
            $errorMessage = "";
        } else{
            $errorMessage = "Aradığınız kullanıcı bulunamadı.";
        }
    }

}

if (empty($errorMessage)) {
    $profileID = $getProfileInfo['id'];
}

$queryCheckIfUserBlocked = $pdo->prepare("SELECT * FROM blocked_users WHERE blocker_id = '$loggedUserID' AND blocked_id = '$profileID'");
$queryCheckIfUserBlocked->execute();

$queryCheckIfUserBlockedMe = $pdo->prepare("SELECT * FROM blocked_users WHERE blocker_id = '$profileID' AND blocked_id = '$loggedUserID'");
$queryCheckIfUserBlockedMe->execute();

if ($queryCheckIfUserBlockedMe->rowCount()) {
    $userBlockedMe = "Yes";
}

if ($queryCheckIfUserBlocked->rowCount()) {
    $userIsBlocked = "Yes";
}

$usernameChangeError = "";

if (isset($_GET['usernameChangeError'])) {
    $usernameChangeError = $_GET['usernameChangeError'];
}

$reportContentFeedbackMessage = "";
$reportUserFeedbackMessage = "";

if (isset($_GET['reportContent'])) {
    $reportContentFeedbackMessage = "Şikayetiniz bize ulaşmıştır. Teşekkür ederiz.";
}

if (isset($_GET['reportUser'])) {
    $reportUserFeedbackMessage = "Şikayetiniz bize ulaşmıştır. Teşekkür ederiz.";
}

// ------------------------ FOLLOW QUERIES ------------------------

$queryFollowers = $pdo->prepare("SELECT * FROM follower WHERE followed_id = '$profileID'");
$queryFollowers->execute();

$queryFollowing = $pdo->prepare("SELECT * FROM follower WHERE follower_id = '$profileID'");
$queryFollowing->execute();

$numberOfFollowers = $queryFollowers->rowCount();
$numberOfFollowing = $queryFollowing->rowCount();

$sqlFollowRequests = "SELECT follow_requests.*, users.id AS user_id,
    users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility
    FROM follow_requests
    LEFT JOIN users ON follow_requests.request_sender = users.id
    WHERE follow_requests.request_getter = '$loggedUserID'
    ORDER BY follow_requests.request_id DESC";
$queryFollowRequests = $pdo->prepare($sqlFollowRequests);
$queryFollowRequests->execute();

$numberOfFollowRequests = $queryFollowRequests->rowCount();

// ------------------------ /FOLLOW QUERIES ------------------------

if (!empty($usernameChangeError)) {

?>

<script>
    $(document).ready(function(){
        $("#editProfile").modal('show');
    });
</script>

<?php }

$lockedProfile = false;

$queryFollowingInfo = $pdo->prepare("SELECT * FROM follower WHERE follower_id = '$loggedUserID' AND followed_id = '$profileID'");
$queryFollowingInfo->execute();

if (empty($errorMessage)) {
    if ($getProfileInfo['profile_lock'] == 1 && $profileID != $loggedUserID) {
        $lockedProfile = true;
    } else {
        $lockedProfile = false;
    }
}

$sqlStatementLastContents = "SELECT contents.*, users.id AS user_id,
    users.username, users.profile_photo, users.profile_lock, users.like_visibility, users.comment_visibility
    FROM contents
    LEFT JOIN users ON contents.publisher_id = users.id
    WHERE contents.publisher_id = '$profileID'
    ORDER BY contents.id DESC";
$queryLastContents = $pdo->prepare($sqlStatementLastContents);
$queryLastContents->execute();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    if ($profileID == $_SESSION["id"]) {
        $myProfile = true;
    } else {
        $myProfile = false;
    }
}

?>

<div class="container">

    <?php
        if (!empty($errorMessage)) {
            echo "<h3 class='text-center margin-top-15'>Aradığınız kullanıcı bulunamadı.</h3>";
            exit;
        }
    ?>

    <div class="row margin-top-15">

        <div class="col-md-3 col-sm-12">

            <!-- USER INFORMATION CARD -->
            <div class="card padding-15 margin-top-15">

                <p class="profileInfoText margin-top-15"><?php echo $getProfileInfo["username"]; ?></p>

                <?php if ($userIsBlocked != "Yes" && $userBlockedMe != "Yes") { ?>

                <p class="margin-top-15"><?php echo $getProfileInfo["biography"]; ?></p>

                <!-- FOLLOWERS AND FOLLOWINGS -->
                <section class="row">

                    <div class="col-6 text-center" data-bs-toggle="modal" data-bs-target="#showFollowers">
                        <div><b><?php echo $numberOfFollowers; ?></b></div>
                        <div>Takipçi</div>
                    </div>

                    <div class="col-6 text-center" data-bs-toggle="modal" data-bs-target="#showFollowings">
                        <div><b><?php echo $numberOfFollowing; ?></b></div>
                        <div>Takip Edilen</div>
                    </div>

                </section>
                <!-- /FOLLOWERS AND FOLLOWINGS -->

                <?php

                } // END OF CHECK IF USER IS BLOCKED

                if ($myProfile) {
                    echo "
                        <button class='btn btn-sm btn-outline-primary margin-top-15' data-bs-toggle='modal' data-bs-target='#editProfile'>
                            Profili Düzenle
                        </button>
                        ";

                    echo "
                        <button class='btn btn-sm btn-outline-primary margin-top-15' data-bs-toggle='modal' data-bs-target='#followRequests'>
                            Takip İstekleri <span class='badge bg-danger'>$numberOfFollowRequests</span>
                        </button>
                        ";
                }

                if (!$myProfile) {

                ?>

                <form action="<?php echo $rootPath; ?>/includes/follower-operations.php" method="post">

                    <input type="hidden" name="followed_id" value="<?php echo $profileID; ?>" />

                    <?php

                    $queryFollowInfo = $pdo->prepare(
                        "SELECT * FROM follower
                        WHERE follower_id = '$loggedUserID' AND followed_id = '$profileID'"
                    );
                    $queryFollowInfo->execute();

                    $queryFollowRequestInfo = $pdo->prepare(
                        "SELECT * FROM follow_requests
                        WHERE request_sender = '$loggedUserID' AND request_getter = '$profileID'"
                    );
                    $queryFollowRequestInfo->execute();

                    if ($userIsBlocked != "Yes" && $userBlockedMe != "Yes") {

                        if ($queryFollowInfo->rowCount() == 1) {

                    ?>

                    <section class="text-center margin-top-15">
                        <button type="submit" class="btn btn-outline-primary" name="unfollow">Takip Ediliyor</button>
                    </section>

                    <?php

                        } else { // END OF CHECK IF USER IS FOLLOWING THIS PROFILE

                            if ($queryFollowRequestInfo->rowCount() != 0) {

                    ?>

                    <section class="text-center margin-top-15">
                        <button type="button" class="btn btn-outline-primary" name="request_sent">İstek Gönderildi</button>
                    </section>

                      <?php } else { ?>

                    <section class="text-center margin-top-15">
                        <button type="submit" class="btn btn-outline-primary" name="follow">Takip Et</button>
                    </section>

<?php

                            }

                        } // END OF NOT FOLLOWING BLOCK

                    } // END OF CHECK IF USER IS BLOCKED

 ?>


                </form>

<?php
                    if ($userIsBlocked != "Yes" && $userBlockedMe != "Yes") {
?>

                    <a
                        href="<?php echo $rootPath; ?>/messages/conversation?with=<?php echo $profileID; ?>"
                        class="btn btn-outline-primary margin-top-15"
                        role="button"
                        aria-pressed="true"
                    >
                        Mesaj
                    </a>

<?php
                    }

                    if ($userIsBlocked == "Yes") {

                            echo "
                                <form action='$rootPath/includes/user-operations.php' method='post'>

                                    <input type='hidden' name='userToUnblock' value='$profileID' />

                                    <section class='text-center'>
                                        <button type='submit' name='unblock_user' class='btn btn-sm btn-outline-danger margin-top-15'>
                                            Engeli Kaldır
                                        </button>
                                    </section>
                                </form>
                            ";

                    } else {

                        if (!$myProfile) {
                                print("<button class='btn btn-sm btn-outline-danger margin-top-15' data-bs-toggle='modal' data-bs-target='#reportUser'>Şikayet Et / Engelle</button>");
                        }

                    }

?>

<?php
                } // CHECK IF IT'S NOT MY PROFILE BLOCK
?>
            </div>
            <!-- /USER INFORMATION CARD -->

        </div>

        <div class="col-md-9 col-sm-12">

            <main>

<?php

                if ($queryCheckIfUserBlocked->rowCount()) {
                    echo "<h3 class='text-center margin-top-15'>Bu kullanıcıyı engellediniz.</h3>";
                } else if ($queryCheckIfUserBlockedMe->rowCount()) {
                    echo "<h3 class='text-center margin-top-15'>Bu kullanıcı tarafından engellediniz.</h3>";
                } else {

                    if (!$lockedProfile || $queryFollowingInfo->rowCount() == 1) {

                        if (isset($_GET["reportContent"])) {
                            echo "
                            <div class='margin-top-15 alert alert-success' role='alert'>
                                $reportContentFeedbackMessage
                            </div>
                            ";
                        }

                        if (isset($_GET["reportUser"])) {
                            echo "
                            <div class='margin-top-15 alert alert-success' role='alert'>
                                $reportUserFeedbackMessage
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

                            $likedFromWhere = "Profile Page";
                            $commentFromWhere = "Profile Page";
                            $reportFromWhere = "Profile Page";

                            include("content-card.php");

                        }

                    } else { // END OF CHECK IF FOLLOWING THE USER

                        echo "<p class='text-center margin-top-10'><b>Bu kullanıcının profili gizli.</b></p>";

                    }

                } // USER IS NOT BLOCKED CODE BLOCK
?>

            </main>

        </div>

    </div>
    <!-- END OF ROW -->

</div>


<?php

include("modal-edit-profile.php");
include("modal-follower-list.php");
include("modal-follow-requests.php");
include("modal-report-user.php");

require_once("includes/footer.php");

?>
