<?php

if (!isset($_SESSION)) {
    session_start();
}

$myProfile = false;
$profileUsername = $_GET["username"];
$errorMessage = "";

$pageTitle = $profileUsername . " profili | Brand";

require_once("includes/header.php");

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
                    <img style="border-radius: 100%;" src="assets/img/profile_photos/<?php echo $getProfileInfo["profile_photo"]; ?>" width="100%" height="100%" />
                </div>
                <p class="profileInfoText margin-top-15"><?php echo $getProfileInfo["username"]; ?></p>
                <?php ($myProfile ? print("<button class='btn btn-sm btn-secondary' data-bs-toggle='modal' data-bs-target='#editProfile'>Profili Düzenle</button>") : ""); ?>
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
        <form method="post" action="includes/user-operations.php">

            <label for="update-username">Kullanıcı Adı</label>
            <div class="input-group mb-3">
                <input class="form-control" type="text" value="<?php echo $getProfileInfo['username']; ?>" aria-describedby="button-username" id="update-username" name="new_username" />
                <button type="submit" name="update_username" class="btn btn-outline-success" type="button" id="button-username">
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
