<?php

if (!isset($_SESSION)) {
    session_start();
}

$myProfile = false;
$profileUsername = $_GET["username"];
$errorMessage = "";
$myUsername = $_SESSION["username"];

$pageTitle = $profileUsername . " profili | Grad Project";

require_once("includes/header.php");

$profileInfo = $pdo->prepare("SELECT * FROM users WHERE username = '$profileUsername'");
$profileInfo->execute();

$getProfileInfo = $profileInfo->fetch(PDO::FETCH_ASSOC);

$profileID = $getProfileInfo['id'];

$queryCheckIfUserBlocked = $pdo->prepare("SELECT * FROM blocked_users WHERE blocker_id = '$loggedUserID' AND blocked_id = '$profileID'");
$queryCheckIfUserBlocked->execute();

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

$queryFollowers = $pdo->prepare("SELECT * FROM follower WHERE followed_id = '$profileID'");
$queryFollowers->execute();

$queryFollowing = $pdo->prepare("SELECT * FROM follower WHERE follower_id = '$profileID'");
$queryFollowing->execute();

$numberOfFollowers = $queryFollowers->rowCount();
$numberOfFollowing = $queryFollowing->rowCount();

if (!empty($usernameChangeError)) {

?>

<script>
    $(document).ready(function(){
        $("#editProfile").modal('show');
    });
</script>

<?php }

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

$lockedProfile = false;

$queryFollowingInfo = $pdo->prepare("SELECT * FROM follower WHERE follower_id = '$loggedUserID' AND followed_id = '$profileID'");
$queryFollowingInfo->execute();

if ($getProfileInfo['profile_lock'] == 1 && $profileID != $loggedUserID) {
    $lockedProfile = true;
} else {
    $lockedProfile = false;
}

$queryLastContents = $pdo->prepare("SELECT * FROM contents WHERE publisher_id = '$profileID' ORDER BY id DESC");
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

            <div class="card padding-15 margin-top-15">
                <div class="center">
                    <img style="border-radius: 100%;" src="/grad-project/assets/img/profile_photos/<?php echo $getProfileInfo["profile_photo"]; ?>" width="100%" height="100%" />
                </div>
                <p class="profileInfoText margin-top-15"><?php echo $getProfileInfo["username"]; ?></p>

                <?php if (!$queryCheckIfUserBlocked->rowCount()) { ?>

                <p class="margin-top-15"><?php echo $getProfileInfo["biography"]; ?></p>

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

                <?php } ?>

                <?php ($myProfile ? print("<button class='btn btn-sm btn-secondary margin-top-15' data-bs-toggle='modal' data-bs-target='#editProfile'>Profili Düzenle</button>") : ""); ?>

                <?php if (!$myProfile) { ?>

                    <form action="/grad-project/includes/follower-operations.php" method="post">

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

                    if (!$queryCheckIfUserBlocked->rowCount()) {

                        if ($queryFollowInfo->rowCount() == 1) {

                        ?>

                        <section class="text-center margin-top-15">
                            <button type="submit" class="btn btn-outline-primary" name="unfollow">Takip Ediliyor</button>
                        </section>

                        <?php } else { ?>

                            <?php if ($queryFollowRequestInfo->rowCount() != 0) { ?>

                                <section class="text-center margin-top-15">
                                    <button type="button" class="btn btn-outline-primary" name="request_sent">İstek Gönderildi</button>
                                </section>

                            <?php } else { ?>

                                <section class="text-center margin-top-15">
                                    <button type="submit" class="btn btn-outline-primary" name="follow">Takip Et</button>
                                </section>

                            <?php } ?>

                        <?php } // END OF NOT FOLLOWING BLOCK ?>

                    <?php } // END OF CHECK IF USER IS BLOCKED ?>

                    </form>

                    <?php if (!$queryCheckIfUserBlocked->rowCount()) { ?>

                    <a
                        href="/grad-project/messages/conversation?with=<?php echo $profileID; ?>"
                        class="btn btn-outline-primary margin-top-15"
                        role="button"
                        aria-pressed="true"
                    >
                        Mesaj
                    </a>

                    <?php } ?>

                    <?php

                        if ($queryCheckIfUserBlocked->rowCount()) {

                            echo "
                                <form action='/grad-project/includes/user-operations.php' method='post'>

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

                <?php } ?>
            </div>

        </div>

        <div class="col-md-9 col-sm-12">

            <main>

                <?php

                if ($queryCheckIfUserBlocked->rowCount()) {
                    echo "<h3 class='text-center margin-top-15'>Bu kullanıcıyı engellediniz.</h3>";
                } else {

                    if (!$lockedProfile || $queryFollowingInfo->rowCount() == 1) { ?>

                <section>

                    <?php

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

                    ?>

                <?php while($getLastContents = $queryLastContents->fetch(PDO::FETCH_ASSOC)) { ?>

                    <?php

                    $contentID = $getLastContents['id'];
                    $publisherID = $profileID;
                    $postUsername = $getProfileInfo['username'];

                    $queryLikesName = "queryLikes" . $contentID;
                    $getLikesName = "getLikes" . $contentID;

                    $queryTotalLikesName = "queryTotalLikes" . $contentID;
                    $getTotalLikesName = "getTotalLikes" . $contentID;

                    $queryFollowName = "queryFollow" . $contentID;
                    $getFollowName = "getFollow" . $contentID;

                    $queryFollowName = $pdo->prepare("SELECT * FROM follower WHERE follower_id = '$loggedUserID' AND followed_id = '$profileID'");
                    $queryFollowName->execute();

                    $canSeePost = true;
                    $canSeeLikes = true;
                    $canSeeComments = true;

                    if ($getProfileInfo['profile_lock'] == 1 && $profileID != $loggedUserID) {
                        if ($queryFollowName->rowCount() == 1) {
                            $canSeePost = true;
                        } else {
                            $canSeePost = false;
                        }
                    }

                    if ($getProfileInfo['like_visibility'] == 0 && $profileID != $loggedUserID) {
                        $canSeeLikes = false;
                    } else {
                        $canSeeLikes = true;
                    }

                    if ($getProfileInfo['comment_visibility'] == 0 && $profileID != $loggedUserID) {
                        $canSeeComments = false;
                    } else {
                        $canSeeComments = true;
                    }

                    ?>

                    <?php

                    $contentID = $getLastContents['id'];
                    $publisherID = $getLastContents['publisher_id'];

                    $queryName = "queryPublisher" . $contentID;
                    $getterName = "getPublisher" . $contentID;

                    $queryLikesName = "queryLikes" . $contentID;
                    $getLikesName = "getLikes" . $contentID;

                    $queryName = $pdo->prepare("SELECT * FROM users WHERE id = $publisherID");
                    $queryName->execute();

                    $getterName = $queryName->fetch(PDO::FETCH_ASSOC);

                    ?>

                    <section class="margin-top-15 card padding-15">

                        <section>
                            <span class="badge bg-light text-dark font-16">
                                <img
                                    style="border-radius: 100%;"
                                    src="/grad-project/assets/img/profile_photos/<?php echo $getterName["profile_photo"]; ?>"
                                    width="25px" height="25px" />
                                <?php echo $getterName["username"]; ?>
                            </span>
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
                                <input type="hidden" name="to_where" value="profile" />

                                <section class="margin-top-15 row text-center content-icons">

                                    <?php

                                        $likedContent = $getLastContents['id'];

                                        $queryLikesName = $pdo->prepare(
                                            "SELECT * FROM liked_contents
                                            WHERE liked_content = $likedContent
                                            AND who_liked = $loggedUserID
                                        ");
                                        $queryLikesName->execute();

                                    ?>

                                    <div class="col-3">

                                        <?php if ($queryLikesName->rowCount() == 1) { ?>

                                        <button type="submit" name="dislike_content" class="content-button">
                                            <i class="fas fa-heart"></i>
                                        </button>

                                        <?php } else { ?>

                                        <button type="submit" name="like_content" class="content-button">
                                            <i class="far fa-heart"></i>
                                        </button>

                                        <?php } ?>

                                    </div>

                                    <?php

                                        $commentFromWhere = "Profile Page";
                                        $reportFromWhere = "Profile Page";

                                        require_once("modal-send-comment.php");
                                        require_once("modal-report-user.php");

                                        ($getLastContents['publisher_id'] == $loggedUserID) ? include("modal-edit-content.php") : include("modal-content-settings.php")
                                    ?>

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

                                    <div class="col-3">
                                        <button
                                            type="button"
                                            class="content-button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#forwardContent<?php echo $getLastContents['id']; ?>"
                                        >
                                            <i class="far fa-share-square"></i>
                                        </button>

                                        <?php $forwardFromWhere = "Profile Page"; ?>
                                        <?php include("modal-forward-content.php"); ?>
                                    </div>

                                    <div class="col-3">
                                        <i class="far fa-plus-square"></i>
                                    </div>

                                </section>

                            </form>
                        </section>

                    </section>

                <?php } ?>

                </section>

            <?php } else { // END OF CHECK IF FOLLOWING THE USER ?>

                <p class="text-center"><b>Bu kullanıcının profili gizli.</b></p>

            <?php } ?>

        <?php } ?>

            </main>

        </div>

    </div>

</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="editProfile" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfile">Profili Düzenle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="/grad-project/includes/user-operations.php" enctype="multipart/form-data">

            <label for="update-profile-photo">Profil Fotoğrafı</label>
            <div class="input-group">
                <input
                    name="new_profile_photo"
                    type="file"
                    class="form-control"
                    id="update-profile-photo"
                    aria-describedby="inputGroupFileAddon04"
                    aria-label="Upload"
                />
                <button class="btn btn-outline-success" type="submit" name="update_profile_photo"><i class="fas fa-save"></i></button>
            </div>

            <br />

            <label for="update-username">Kullanıcı Adı</label>
            <div class="input-group mb-3">
                <input
                    class="form-control"
                    type="text"
                    value="<?php echo $getProfileInfo['username']; ?>"
                    aria-describedby="button-username"
                    id="update-username"
                    name="new_username"
                    maxlength="16"
                    required
                />
                <button type="submit" name="update_username" class="btn btn-outline-success" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>
            <p class="text-danger"><?php echo $usernameChangeError; ?></p>

            <label for="update-username">Biyografi</label>
            <div class="input-group mb-3">
                <input
                    class="form-control"
                    type="text"
                    value="<?php echo trim($getProfileInfo['biography']); ?>"
                    aria-describedby="button-username"
                    id="update-biography"
                    name="new_biography"
                    maxlength="180"
                />
                <button type="submit" name="update_biography" class="btn btn-outline-success" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit Profile Modal -->


<!-- Show Followers Modal -->
<div class="modal fade" id="showFollowers" tabindex="-1" aria-labelledby="showFollowers" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showFollowers">Takipçiler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

          <section class="padding-15 text-center row">

          <?php while($getFollowers = $queryFollowers->fetch(PDO::FETCH_ASSOC)) { ?>

              <?php

                $followID = $getFollowers['id'];
                $followerID = $getFollowers['follower_id'];

                $queryNameFollower = "queryFollowerList" . $followID;
                $getterNameFollower = "getFollowerList" . $followID;

                $queryNameFollower = $pdo->prepare("SELECT * FROM users WHERE id = '$followerID'");
                $queryNameFollower->execute();

                $getterNameFollower = $queryNameFollower->fetch(PDO::FETCH_ASSOC);

              ?>


              <div class="col-6 text-center">
                  <div class="text-center margin-top-15">
                      <a href="/grad-project/user/<?php echo $getterNameFollower['username']; ?>" class="my-links">
                          <span class="badge bg-light text-dark font-16">
                              <img
                                  style="border-radius: 100%;"
                                  src="/grad-project/assets/img/profile_photos/<?php echo $getterNameFollower["profile_photo"]; ?>"
                                  width="40px" height="40px" />
                              <?php echo $getterNameFollower["username"]; ?>
                          </span>
                      </a>
                  </div>
              </div>


          <?php } ?>

          </section>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- /Show Followers Modal -->


<!-- Show Followings Modal -->
<div class="modal fade" id="showFollowings" tabindex="-1" aria-labelledby="showFollowings" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showFollowings">Takip Edilenler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

          <section class="padding-15 text-center row">

          <?php while($getFollowings = $queryFollowing->fetch(PDO::FETCH_ASSOC)) { ?>

              <?php

                $followID = $getFollowings['id'];
                $followingID = $getFollowings['followed_id'];

                $queryNameFollowing = "queryFollowingList" . $followID;
                $getterNameFollowing = "getFollowingList" . $followID;

                $queryNameFollowing = $pdo->prepare("SELECT * FROM users WHERE id = '$followingID'");
                $queryNameFollowing->execute();

                $getterNameFollowing = $queryNameFollowing->fetch(PDO::FETCH_ASSOC);

              ?>


              <div class="col-6 text-center">
                  <div class="text-center margin-top-15">
                      <a href="/grad-project/user/<?php echo $getterNameFollowing['username']; ?>" class="my-links margin-top-15">
                          <span class="badge bg-light text-dark font-16">
                              <img
                                  style="border-radius: 100%;"
                                  src="/grad-project/assets/img/profile_photos/<?php echo $getterNameFollowing["profile_photo"]; ?>"
                                  width="40px" height="40px" />
                              <?php echo $getterNameFollowing["username"]; ?>
                          </span>
                      </a>
                  </div>
              </div>


          <?php } ?>

          </section>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- /Show Followings Modal -->

<?php require_once("includes/footer.php"); ?>
