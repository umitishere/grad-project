<?php

$pageTitle = "Gelen Kutusu | Grad Project";

require_once("includes/header.php");

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: $rootPath/giris-yap");
    exit;
}

$sessionID = $_SESSION["id"];

$queryMessages = $pdo->prepare(
    "SELECT *
    FROM messages
    LEFT JOIN users
    ON (
        IF (
            messages.message_sender = '$sessionID',
            messages.message_getter = users.id,
            messages.message_sender = users.id
        )
    )
    WHERE (
        IF (
            messages.message_sender = '$sessionID',
            messages.message_sender = '$sessionID',
            messages.message_getter = '$sessionID'
        )
    )
    GROUP BY (
        IF (
            messages.message_sender = '$sessionID',
            messages.message_getter,
            messages.message_sender
        )
    )
    ORDER BY messages.id DESC
"
);
$queryMessages->execute();

?>

<div class="container">

    <section class="padding-15 margin-top-15 card">

        <h3 class="margin-top-15 text-center">Sohbetler</h3>
        <hr />

        <section class="message-area padding-15">
        <?php while ($getMessages = $queryMessages->fetch(PDO::FETCH_ASSOC)) {

            $messageYear = substr($getMessages['message_time'], 0, 4);
            $messageMonth = substr($getMessages['message_time'], 5, 2);
            $messageDay = substr($getMessages['message_time'], 8, 2);

            $messageHour = substr($getMessages['message_time'], 11, 2);
            $messageMinute = substr($getMessages['message_time'], 14, 2);

            if ($getMessages['message_sender'] != $sessionID) { ?>

            <section class="margin-top-15 card padding-15">
                <div>
                    <span><b><?php echo $getMessages['username']; ?></b></span>
                    <span><i class="fas fa-clock"></i> <?php echo $messageHour . ":" .$messageMinute; ?></span>
                </div>

                <a href="conversation?with=<?php echo $getMessages['message_sender']; ?>">
                    <button type="button" class="btn btn-primary margin-top-15">Mesajları Görüntüle</button>
                </a>
            </section>

            <?php } else { ?>

                <section class="margin-top-15 card padding-15">
                    <div>
                        <span><b><?php echo $getMessages['username']; ?></b></span>
                        <span><i class="fas fa-clock"></i> <?php echo $messageHour . ":" .$messageMinute; ?></span>
                    </div>

                    <a href="conversation?with=<?php echo $getMessages['message_getter']; ?>">
                        <button type="button" class="btn btn-primary margin-top-15">Mesajları Görüntüle</button>
                    </a>

                </section>

            <?php } ?>

        <?php } ?>
        </section>

    </section>

</div>

<?php require_once("includes/footer.php"); ?>
