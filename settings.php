<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: giris-yap");
}

$pageTitle = "Ayarlar | Brand";

require_once("includes/header.php");

?>

<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12">

            <p class="text-center">Profil içeriği</p>

        </div>

        <div class="col-md-3 col-sm-12">

            <p class="text-center">Yan içerik</p>

        </div>

    </div>

</div>

<?php require_once("includes/footer.php"); ?>
