<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: giris-yap");
}

$pageTitle = "Ayarlar | Grad Project";

require_once("includes/header.php");

?>

<div class="container">

    <h3 class="text-center margin-top-15">Ayarlar</h3>
    <hr />

    <div class="alert alert-primary" role="alert">
        <h5 class="text-center margin-top-15">Gizlilik Ayarları</h5>
        <hr />

        <form class="text-center" action="/grad-project/includes/user-operations.php" method="post">

            <label><b>Kilitli Profil</b></label>
            <div class="input-group mb-3">
                <select class="form-select">
                    <option selected>Sadece takipçilerim gönderilerimi görebilsin</option>
                    <option value="1">Evet</option>
                    <option value="0">Hayır</option>
                </select>
                <button type="submit" name="update_profile_lock" class="btn btn-outline-primary" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>

            <label><b>Beğenileri Göster/Gizle</b></label>
            <div class="input-group mb-3">
                <select class="form-select">
                    <option selected>Gelen beğenileri sadece ben görebileyim</option>
                    <option value="1">Evet</option>
                    <option value="0">Hayır</option>
                </select>
                <button type="submit" name="update_like_visibility" class="btn btn-outline-primary" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>

            <label><b>Yorumları Göster/Gizle</b></label>
            <div class="input-group mb-3">
                <select class="form-select">
                    <option selected>Gelen yorumları sadece ben görebileyim</option>
                    <option value="1">Evet</option>
                    <option value="0">Hayır</option>
                </select>
                <button type="submit" name="update_comment_visibility" class="btn btn-outline-primary" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>

        </form>
    </div>

</div>

<?php require_once("includes/footer.php"); ?>
