<?php

require_once("VARIABLES_EVERYWHERE.php");

$pageTitle = "Gelen Kutusu";

require_once("includes/header.php");

$sessionID = $_SESSION["id"];

$queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$sessionID'");
$queryUserInfo->execute();

$getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

$myUsername = $getUserInfo["username"];

$queryMessages = $pdo->prepare(
    "SELECT *
    FROM messages
    INNER JOIN users
    ON messages.message_sender = users.username
    WHERE (messages.message_sender = '$myUsername' OR messages.message_getter = '$myUsername')
    AND messages.delete_key = '$sessionID'
    GROUP BY messages.message_sender
    ORDER BY messages.id DESC"
);
$queryMessages->execute();

?>

<div class="container">

    <section class="padding-15 margin-top-15 card">

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

            <?php } ?>

        <?php } ?>
        </section>

    </section>

</div>

<?php require_once("includes/footer.php"); ?>
