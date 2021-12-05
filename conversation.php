<?php

$conversationWith = $_GET["with"];

$pageTitle = $conversationWith . " ile sohbet";

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
    WHERE ((messages.message_sender = '$myUsername' AND messages.message_getter = '$conversationWith') 
    OR (messages.message_sender = '$conversationWith' AND messages.message_getter = '$myUsername')) 
    AND messages.delete_key = '$sessionID'
    ORDER BY messages.id DESC" 
);
$queryMessages->execute();

?>

<div class="container">

    <section class="padding-15 margin-top-30">
        <form action="/graduation-project-web/includes/send-message.php" method="post">

            <input type="hidden" name="message_getter" value="<?php echo $conversationWith; ?>" />

            <div class="form-group">
              <label for="message"><b>Mesaj Gönder</b></label>
              <textarea class="form-control" id="message" name="message_detail" rows="2"></textarea>
            </div>

            <div class="text-center margin-top-15">
                <button type="submit" name="send_message" class="btn btn-lg button-color1">Gönder</button>
            </div>

        </form>
    </section>

    <section class="padding-15 card">

        <?php while ($getMessages = $queryMessages->fetch(PDO::FETCH_ASSOC)) { ?>

        <section class="margin-top-15">
            <div class="<?php ($getMessages['message_sender'] == $myUsername) ? print('text-on-right') : print('text-on-left') ?>">
                <span><img class="image-message-sender" src="/graduation-project-web/assets/img/profile_photos/<?php echo $getMessages['profile_photo']; ?>" /> <b><?php echo $getMessages['message_sender']; ?></b></span> 
                <span><?php echo $getMessages['message_time']; ?></span>
            </div>
            <div class="<?php ($getMessages['message_sender'] == $myUsername) ? print('text-on-right') : print('text-on-left') ?>">
                <div class="<?php ($getMessages['message_sender'] == $myUsername) ? print('message-box-1') : print('message-box-2') ?> padding-15 margin-top-15">
                    <?php echo $getMessages['message_detail']; ?>
                </div>
            </div>
        </section>

        <?php } ?>

    </section>

</div>

<?php require_once("includes/footer.php"); ?>
