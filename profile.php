<?php

require_once("VARIABLES_EVERYWHERE.php");

if (!isset($_SESSION)) {
    session_start();
}

$myProfile = false;
$profileUsername = $_GET["username"];
$errorMessage = "";
$myUsername = $_SESSION["username"];

$usernameChangeError = "";

if (isset($_GET['usernameChangeError'])) {
    $usernameChangeError = $_GET['usernameChangeError'];
}

$pageTitle = $profileUsername . " profili | Grad Project";

require_once("includes/header.php");

$queryFollowers = $pdo->prepare("SELECT * FROM follower WHERE followed_name = '$profileUsername'");
$queryFollowers->execute();

$queryFollowing = $pdo->prepare("SELECT * FROM follower WHERE follower_name = '$profileUsername'");
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

$profileInfo = $pdo->prepare("SELECT * FROM users WHERE username = '$profileUsername'");
$profileInfo->execute();

$getProfileInfo = $profileInfo->fetch(PDO::FETCH_ASSOC);

$lockedProfile = false;

$queryFollowingInfo = $pdo->prepare("SELECT * FROM follower WHERE follower_name = '$loggedUsername' AND followed_name = '$profileUsername'");
$queryFollowingInfo->execute();

if ($getProfileInfo['profile_lock'] == 1 && $getProfileInfo['username'] != $loggedUsername) {
    $lockedProfile = true;
} else {
    $lockedProfile = false;
}

$profileID = 0;

if (empty($errorMessage)) {
    $profileID = $getProfileInfo["id"];
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
            echo "<h1 class='text-center margin-top-15'>Aradığınız kullanıcı bulunamadı.</h1>";
            exit;
        }
    ?>

    <div class="row margin-top-15">

        <div class="col-md-3 col-sm-12">

            <div class="card padding-15 margin-top-15">
                <div class="center">
                    <img style="border-radius: 100%;" src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getProfileInfo["profile_photo"]; ?>" width="100%" height="100%" />
                </div>
                <p class="profileInfoText margin-top-15"><?php echo $getProfileInfo["username"]; ?></p>
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

                <?php ($myProfile ? print("<button class='btn btn-sm btn-secondary margin-top-15' data-bs-toggle='modal' data-bs-target='#editProfile'>Profili Düzenle</button>") : ""); ?>

                <?php if (!$myProfile) { ?>

                    <form action="/<?php echo $projectName; ?>/includes/follower-operations.php" method="post">

                        <input type="hidden" name="followed_person" value="<?php echo $profileUsername; ?>" />

                        <?php

                        $queryFollowInfo = $pdo->prepare(
                            "SELECT * FROM follower
                            WHERE follower_name = '$myUsername' AND followed_name = '$profileUsername'"
                        );
                        $queryFollowInfo->execute();

                        if ($queryFollowInfo->rowCount() == 1) {

                        ?>

                        <section class="text-center margin-top-15">
                            <button type="submit" class="btn btn-primary" name="unfollow">Takip Ediliyor</button>
                        </section>

                        <?php } else { ?>

                        <section class="text-center margin-top-15">
                            <button type="submit" class="btn btn-primary" name="follow">Takip Et</button>
                        </section>

                        <?php } ?>

                    </form>

                    <a
                        href="/<?php echo $projectName; ?>/messages/conversation?with=<?php echo $profileUsername; ?>"
                        class="btn btn-primary margin-top-15"
                        role="button"
                        aria-pressed="true"
                    >
                        Mesaj
                    </a>

                <?php } ?>
            </div>

        </div>

        <div class="col-md-9 col-sm-12">

            <main>

                <?php if (!$lockedProfile || $queryFollowingInfo->rowCount() == 1) { ?>

                <section>

                <?php while($getLastContents = $queryLastContents->fetch(PDO::FETCH_ASSOC)) { ?>

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
                                    src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getterName["profile_photo"]; ?>"
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
                                <?php echo nl2br($getLastContents['content_detail']); ?>
                            </section>

                            <form action="/<?php echo $projectName; ?>/includes/content-operations.php" method="post">

                                <input type="hidden" name="liked_content" value="<?php echo $getLastContents['id']; ?>" />
                                <input type="hidden" name="from_where" value="profile" />

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

                                    <?php include("modal-send-comment.php"); ?>

                                    <?php ($getLastContents['publisher_id'] == $loggedUserID) ? include("modal-edit-content.php") : include("modal-content-settings.php") ?>

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
                                        <i class="far fa-share-square"></i>
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

            <?php } else { ?>

                <p class="text-center"><b>Bu kullanıcının profili gizlidir.</b></p>

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
        <form method="post" action="/<?php echo $projectName; ?>/includes/user-operations.php" enctype="multipart/form-data">

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
                $followerName = $getFollowers['follower_name'];

                $queryNameFollower = "queryFollowerList" . $followID;
                $getterNameFollower = "getFollowerList" . $followID;

                $queryNameFollower = $pdo->prepare("SELECT * FROM users WHERE username = '$followerName'");
                $queryNameFollower->execute();

                $getterNameFollower = $queryNameFollower->fetch(PDO::FETCH_ASSOC);

              ?>


              <div class="col-6 text-center">
                  <div class="text-center margin-top-15">
                      <a href="/<?php echo $projectName; ?>/user/<?php echo $getterNameFollower['username']; ?>" class="my-links">
                          <span class="badge bg-light text-dark font-16">
                              <img
                                  style="border-radius: 100%;"
                                  src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getterNameFollower["profile_photo"]; ?>"
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
                $followingName = $getFollowings['followed_name'];

                $queryNameFollowing = "queryFollowingList" . $followID;
                $getterNameFollowing = "getFollowingList" . $followID;

                $queryNameFollowing = $pdo->prepare("SELECT * FROM users WHERE username = '$followingName'");
                $queryNameFollowing->execute();

                $getterNameFollowing = $queryNameFollowing->fetch(PDO::FETCH_ASSOC);

              ?>


              <div class="col-6 text-center">
                  <div class="text-center margin-top-15">
                      <a href="/<?php echo $projectName; ?>/user/<?php echo $getterNameFollowing['username']; ?>" class="my-links margin-top-15">
                          <span class="badge bg-light text-dark font-16">
                              <img
                                  style="border-radius: 100%;"
                                  src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getterNameFollowing["profile_photo"]; ?>"
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
