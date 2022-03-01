<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: giris-yap");
}

$pageTitle = "Bildirimler | Grad Project";

require_once("includes/header.php");

$queryAllNotifications = $pdo->prepare("SELECT * FROM notifications
    WHERE notification_getter_id = '$loggedUserID'
    ORDER BY notification_id DESC");
$queryAllNotifications->execute();

?>

<div class="container">

    <h3 class="text-center margin-top-15">Bildirimler</h3>
    <hr />

    <?php while ($getAllNotifications = $queryAllNotifications->fetch(PDO::FETCH_ASSOC)) { ?>

        <section class="alert alert-dark">
            <?php echo $getAllNotifications['notification_detail']; ?>
        </section>

    <?php } ?>

</div>

<?php require_once("includes/footer.php"); ?>
