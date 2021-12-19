<?php

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

$pageTitle = $profileUsername . " profili | Brand";

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

$profileID = $getProfileInfo["id"];

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
                    <img style="border-radius: 100%;" src="/<?php echo $rootName; ?>/assets/img/profile_photos/<?php echo $getProfileInfo["profile_photo"]; ?>" width="100%" height="100%" />
                </div>
                <p class="profileInfoText margin-top-15"><?php echo $getProfileInfo["username"]; ?></p>
                <p class="margin-top-15"><?php echo $getProfileInfo["biography"]; ?></p>

                <section class="row">
                    <div class="col-6 text-center">
                        <div><b><?php echo $numberOfFollowers; ?></b></div>
                        <div>Takipçi</div>
                    </div>
                    <div class="col-6 text-center">
                        <div><b><?php echo $numberOfFollowing; ?></b></div>
                        <div>Takip Edilen</div>
                    </div>
                </section>

                <?php ($myProfile ? print("<button class='btn btn-sm btn-secondary margin-top-15' data-bs-toggle='modal' data-bs-target='#editProfile'>Profili Düzenle</button>") : ""); ?>

                <?php if (!$myProfile) { ?>

                    <form action="/graduation-project-web/includes/follower-operations.php" method="post">

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
                        href="/<?php echo $rootName; ?>/messages/conversation?with=<?php echo $profileUsername; ?>"
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

            <div class="card padding-15 margin-top-15">

            </div>

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
        <form method="post" action="includes/user-operations.php" enctype="multipart/form-data">

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

<?php require_once("includes/footer.php"); ?>
