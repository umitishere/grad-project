<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: giris-yap");
}

$pageTitle = "Ayarlar | Grad Project";

require_once("includes/header.php");

$queryProfileStatus = $pdo->prepare("SELECT * FROM users WHERE id = '$loggedUserID'");
$queryProfileStatus->execute();

$getProfileStatus = $queryProfileStatus->fetch(PDO::FETCH_ASSOC);

$queryMutedUsers = $pdo->prepare(
    "SELECT muted_users.*, users.* FROM muted_users
    LEFT JOIN users
    ON muted_users.muted_user_id = users.id
    WHERE muter_id = '$loggedUserID'"
);
$queryMutedUsers->execute();

?>

<div class="container">

    <h3 class="text-center margin-top-15">Ayarlar</h3>
    <hr />

    <div class="alert alert-primary" role="alert">

        <h5 class="text-center margin-top-15">Gizlilik Ayarları</h5>
        <hr />

        <form class="text-center" action="<?php echo $rootPath; ?>/includes/user-operations.php" method="post">

            <label><b>Kilitli Profil</b></label>
            <div class="input-group mb-3">
                <select name="profile_lock" class="form-select">
                    <option value="1" <?php $getProfileStatus['profile_lock'] == 1 ? print('selected') : '' ?>>Evet</option>
                    <option value="0" <?php $getProfileStatus['profile_lock'] == 0 ? print('selected') : '' ?>>Hayır</option>
                </select>
                <button type="submit" name="update_profile_lock" class="btn btn-outline-primary" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>

            <label><b>Beğenileri Göster/Gizle</b></label>
            <div class="input-group mb-3">
                <select name="like_visibility" class="form-select">
                    <option value="1" <?php $getProfileStatus['like_visibility'] == 1 ? print('selected') : '' ?>>Evet</option>
                    <option value="0" <?php $getProfileStatus['like_visibility'] == 0 ? print('selected') : '' ?>>Hayır</option>
                </select>
                <button type="submit" name="update_like_visibility" class="btn btn-outline-primary" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>

            <label><b>Yorumları Göster/Gizle</b></label>
            <div class="input-group mb-3">
                <select name="comment_visibility" class="form-select">
                    <option value="1" <?php $getProfileStatus['comment_visibility'] == 1 ? print('selected') : '' ?>>Evet</option>
                    <option value="0" <?php $getProfileStatus['comment_visibility'] == 0 ? print('selected') : '' ?>>Hayır</option>
                </select>
                <button type="submit" name="update_comment_visibility" class="btn btn-outline-primary" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>

        </form>

        <h5 class="text-center margin-top-15">Sessize Alınan Hesaplar</h5>
        <hr />

        <p class="text-center margin-top-15">Susturmayı kaldırmak için kullanıcının üzerine tıklayın.</p>

<?php while ($getMutedUsers = $queryMutedUsers->fetch(PDO::FETCH_ASSOC)) { ?>

        <section class="text-center margin-top-15">

            <form action="<?php echo $rootPath; ?>/includes/content-operations.php" method="post">

                <input type="hidden" name="user_to_unmute" value="<?php echo $getMutedUsers["muted_user_id"]; ?>" />

                <button type="submit" name="unmute_user" class="btn btn-light"><?php echo $getMutedUsers["username"]; ?></button>

            </form>

        </section>

<?php } ?>
    </div>

</div>

<?php require_once("includes/footer.php"); ?>
