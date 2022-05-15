<?php

require_once("includes/config.php");

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: giris-yap");
}

$querySupportInfo = $pdo->prepare("SELECT * FROM users WHERE username = 'destek'");
$querySupportInfo->execute();

$getSupportInfo = $querySupportInfo->fetch(PDO::FETCH_ASSOC);

$pageTitle = "Destek | Grad Project";

require_once("includes/header.php");

?>

<div class="container">

    <h3 class="text-center margin-top-15">Destek</h3>

    <!-- SEND MESSAGE -->
    <section class="padding-15 margin-top-15">
        <form action="<?php echo $rootPath; ?>/includes/send-message.php" method="post">

            <input type="hidden" name="message_getter" value="<?php echo $getSupportInfo["id"]; ?>" />
            <input type="hidden" name="isThisPost" value="0" />

            <div class="form-group">
            <label for="message"><b>Mesaj</b></label>
            <textarea class="form-control" id="message" name="message_detail" rows="4"></textarea>
            </div>

            <div class="text-center margin-top-15">
                <button type="submit" name="send_message" class="btn button-color1">Gönder</button>
            </div>

        </form>
    </section>
    <!-- /SEND MESSAGE -->

</div>

<?php require_once("includes/footer.php"); ?>
