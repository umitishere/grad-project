<?php

$conversationWithID = $_GET["with"];

$pageTitle = "Sohbet | Grad Project";

require_once("includes/header.php");

$sessionID = $_SESSION["id"];

/* GET MY AND OTHER USER INFO */

$queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$sessionID'");
$queryUserInfo->execute();

$getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

$myUsername = $getUserInfo["username"];

$myID = $sessionID;

/* GET MY AND OTHER USER INFO */



$queryMessages = $pdo->prepare(
    "SELECT users.username, users.profile_photo, messages.*
    FROM messages
    INNER JOIN users
    ON messages.message_sender = users.id
    WHERE ((messages.message_sender = '$sessionID' AND messages.message_getter = '$conversationWithID')
    OR (messages.message_sender = '$conversationWithID' AND messages.message_getter = '$sessionID'))
    AND messages.delete_key = '$sessionID'
    ORDER BY messages.id DESC"
);
$queryMessages->execute();

?>

<div class="container">

    <section class="padding-15 margin-top-15 card">

        <section class="conversation-area padding-15">
        <?php while ($getMessages = $queryMessages->fetch(PDO::FETCH_ASSOC)) { ?>

            <?php

            $messageYear = substr($getMessages['message_time'], 0, 4);
            $messageMonth = substr($getMessages['message_time'], 5, 2);
            $messageDay = substr($getMessages['message_time'], 8, 2);

            $messageHour = substr($getMessages['message_time'], 11, 2);
            $messageMinute = substr($getMessages['message_time'], 14, 2);

            ?>

            <?php if ($getMessages['isThisPost'] == 1) { ?>

                <section class="margin-top-15 padding-15">
                    <div class="<?php ($getMessages['message_sender'] == $myID) ? print('text-on-right') : print('text-on-left') ?>">
                        <span><img class="image-message-sender" src="/grad-project/assets/img/profile_photos/<?php echo $getMessages['profile_photo']; ?>" /> <b><?php echo $getMessages['username']; ?></b></span>
                        <span><i class="fas fa-clock"></i> <?php echo $messageHour . ":" .$messageMinute; ?></span>

                        <p class="font-12 margin-top-10"><i class="fas fa-share-square"></i> bir gönderi paylaştı</p>
                    </div>
                    <div class="<?php ($getMessages['message_sender'] == $myID) ? print('text-on-right') : print('text-on-left') ?>">
                        <div class="padding-15 card margin-top-15">
                            <?php echo $getMessages['message_detail']; ?>
                        </div>
                        <button type="button" class="btn btn-danger margin-top-15" data-bs-toggle="modal" data-bs-target="#deleteMessage<?php echo $getMessages['id']; ?>">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </section>

            <?php } else { ?>

                <section class="margin-top-15 card padding-15">
                    <div class="<?php ($getMessages['message_sender'] == $myID) ? print('text-on-right') : print('text-on-left') ?>">
                        <span><img class="image-message-sender" src="/grad-project/assets/img/profile_photos/<?php echo $getMessages['profile_photo']; ?>" /> <b><?php echo $getMessages['username']; ?></b></span>
                        <span><i class="fas fa-clock"></i> <?php echo $messageHour . ":" .$messageMinute; ?></span>
                    </div>
                    <div class="<?php ($getMessages['message_sender'] == $myID) ? print('text-on-right') : print('text-on-left') ?>">
                        <div class="<?php ($getMessages['message_sender'] == $myID) ? print('message-box-1') : print('message-box-2') ?> padding-15 margin-top-15">
                            <?php echo $getMessages['message_detail']; ?>
                        </div>
                        <button type="button" class="btn btn-danger margin-top-15" data-bs-toggle="modal" data-bs-target="#deleteMessage<?php echo $getMessages['id']; ?>">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </section>

            <?php } ?>

            <!-- Modal DELETE MESSAGE -->
            <div class="modal fade" id="deleteMessage<?php echo $getMessages['id']; ?>" tabindex="-1" aria-labelledby="deleteMessage<?php echo $getMessages['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteMessage<?php echo $getMessages['id']; ?>">Mesajı sil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/grad-project/includes/send-message.php" method="post">

                                <input type="hidden" value="<?php echo $getMessages['unique_name']; ?>" name="unique" />
                                <input type="hidden" value="<?php echo $conversationWithID; ?>" name="conversation_with" />

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

        <!-- SEND MESSAGE -->
        <section class="padding-15 margin-top-30">
            <form action="/grad-project/includes/send-message.php" method="post">

                <input type="hidden" name="message_getter" value="<?php echo $conversationWithID; ?>" />
                <input type="hidden" name="isThisPost" value="0" />

                <div class="form-group">
                <label for="message"><b>Mesaj Gönder</b></label>
                <textarea class="form-control" id="message" name="message_detail" rows="2"></textarea>
                </div>

                <div class="text-center margin-top-15">
                    <button type="submit" name="send_message" class="btn btn-lg button-color1">Gönder</button>
                </div>

            </form>
        </section>
        <!-- /SEND MESSAGE -->

    </section>

</div>

<?php require_once("includes/footer.php"); ?>
