<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: giris-yap");
}

$pageTitle = "Gruplar | Grad Project";

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

    <h3 class="text-center margin-top-15">Gruplar</h3>
    <hr />

    <section class="text-center margin-top-15">
        <button class="btn btn-outline-success" data-bs-toggle='modal' data-bs-target='#createGroup'>
            <i class="fas fa-plus"></i> Grup Oluştur
        </button>
    </section>

<?php require_once("modal-create-group.php"); ?>

</div>

<?php require_once("includes/footer.php"); ?>
