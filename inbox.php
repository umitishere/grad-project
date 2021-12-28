<?php

require_once("VARIABLES_EVERYWHERE.php");

$pageTitle = "Gelen Kutusu | Grad Project";

require_once("includes/header.php");

$sessionID = $_SESSION["id"];

$queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$sessionID'");
$queryUserInfo->execute();

$getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

$myUsername = $getUserInfo["username"];

/* $queryMessages = $pdo->prepare(
    "SELECT *
    FROM messages
    INNER JOIN users
    ON messages.message_sender = users.username
    WHERE (messages.message_sender = '$myUsername')
    AND messages.delete_key = '$sessionID'
    GROUP BY messages.message_sender OR messages.message_getter
    ORDER BY messages.id DESC"
); */
$queryMessages = $pdo->prepare(
    "SELECT *
    FROM messages
    INNER JOIN users
    ON (
        IF (
            messages.message_sender = '$myUsername',
            messages.message_getter = users.username,
            messages.message_sender = users.username
        )
    )
    WHERE (
        IF (
            messages.message_sender = '$myUsername',
            messages.message_sender = '$myUsername',
            messages.message_getter = '$myUsername'
        )
    )
    GROUP BY (
        IF (
            messages.message_sender = '$myUsername',
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
        <?php while ($getMessages = $queryMessages->fetch(PDO::FETCH_ASSOC)) { ?>

            <?php if ($getMessages['message_sender'] != $myUsername) { ?>

            <section class="margin-top-15 card padding-15">
                <div>
                    <span><img class="image-message-sender" src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getMessages['profile_photo']; ?>" /> <b><?php echo $getMessages['message_sender']; ?></b></span>
                    <span><?php echo $getMessages['message_time']; ?></span>
                </div>

                <a href="conversation?with=<?php echo $getMessages['message_sender']; ?>">
                    <div class="message-box-1 padding-15 margin-top-15">
                        <?php echo $getMessages['message_detail']; ?>
                    </div>
                </a>

            </section>

            <?php } else { ?>

                <section class="margin-top-15 card padding-15">
                    <div>
                        <span><img class="image-message-sender" src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getMessages['profile_photo']; ?>" /> <b><?php echo $getMessages['message_getter']; ?></b></span>
                        <span><?php echo $getMessages['message_time']; ?></span>
                    </div>

                    <a href="conversation?with=<?php echo $getMessages['message_getter']; ?>">
                        <div class="message-box-1 padding-15 margin-top-15">
                            <?php echo $getMessages['message_detail']; ?>
                        </div>
                    </a>

                </section>

            <?php } ?>

        <?php } ?>
        </section>

    </section>

</div>

<?php require_once("includes/footer.php"); ?>
