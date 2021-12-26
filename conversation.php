<?php

require_once("VARIABLES_EVERYWHERE.php");

$conversationWith = $_GET["with"];

$pageTitle = $conversationWith . " ile sohbet";

require_once("includes/header.php");

$sessionID = $_SESSION["id"];

$queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$sessionID'");
$queryUserInfo->execute();

$getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

$myUsername = $getUserInfo["username"];

$queryMessages = $pdo->prepare(
    "SELECT users.username, users.profile_photo, messages.*
    FROM messages
    INNER JOIN users
    ON messages.message_sender = users.username
    WHERE ((messages.message_sender = '$myUsername' AND messages.message_getter = '$conversationWith')
    OR (messages.message_sender = '$conversationWith' AND messages.message_getter = '$myUsername'))
    AND messages.delete_key = '$sessionID'
    ORDER BY messages.id ASC"
);
$queryMessages->execute();

?>

<div class="container">

    <section class="padding-15 margin-top-15 card">

        <section class="conversation-area padding-15">
        <?php while ($getMessages = $queryMessages->fetch(PDO::FETCH_ASSOC)) { ?>

            <section class="margin-top-15 card padding-15">
                <div class="<?php ($getMessages['message_sender'] == $myUsername) ? print('text-on-right') : print('text-on-left') ?>">
                    <span><img class="image-message-sender" src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getMessages['profile_photo']; ?>" /> <b><?php echo $getMessages['message_sender']; ?></b></span>
                    <span><?php echo $getMessages['message_time']; ?></span>
                </div>
                <div class="<?php ($getMessages['message_sender'] == $myUsername) ? print('text-on-right') : print('text-on-left') ?>">
                    <div class="<?php ($getMessages['message_sender'] == $myUsername) ? print('message-box-1') : print('message-box-2') ?> padding-15 margin-top-15">
                        <?php echo $getMessages['message_detail']; ?>
                    </div>
                    <button type="button" class="btn btn-danger margin-top-15" data-bs-toggle="modal" data-bs-target="#deleteMessage<?php echo $getMessages['id']; ?>">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </section>

            <!-- Modal DELETE MESSAGE -->
            <div class="modal fade" id="deleteMessage<?php echo $getMessages['id']; ?>" tabindex="-1" aria-labelledby="deleteMessage<?php echo $getMessages['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteMessage<?php echo $getMessages['id']; ?>">Mesajı sil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/<?php echo $projectName; ?>/includes/send-message.php" method="post">

                                <input type="hidden" value="<?php echo $getMessages['unique_name']; ?>" name="unique" />

                                <input type="hidden" value="<?php echo $conversationWith; ?>" name="conversation_with" />

                                <section class="text-center">
                                    <button type="submit" class="btn btn-lg btn-danger margin-top-15" name="delete_message_for_me">
                                        Benden Sil
                                    </button>
                                    <button type="submit" class="btn btn-lg btn-danger margin-top-15" name="delete_message_for_everyone">
                                        Herkesten Sil
                                    </button>
                                </section>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
        </section>

        <section class="padding-15 margin-top-30">
            <form action="/<?php echo $projectName; ?>/includes/send-message.php" method="post">

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

    </section>

</div>

<?php require_once("includes/footer.php"); ?>
