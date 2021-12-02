<?php

$conversationWith = $_GET["with"];

$pageTitle = $conversationWith . " ile sohbet";

require_once("includes/header.php");

$sessionID = $_SESSION["id"];

$queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$sessionID'");
$queryUserInfo->execute();

$getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

$myUsername = $getUserInfo["username"];

$queryMessages = $pdo->prepare("SELECT * FROM messages WHERE message_sender = '$myUsername' AND message_getter = '$conversationWith' AND delete_key = '$sessionID'");
$queryMessages->execute();

?>

<div class="container">

    <div class="card padding-15">
        <form action="includes/send-message.php" method="post">

            <div class="form-group">
              <label for="exampleFormControlTextarea1">Mesaj Gönder</label>
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>

        </form>
    </div>

    <?php while ($getMessages = $queryMessages->fetch(PDO::FETCH_ASSOC)) { ?>

    <div class="card">

    </div>

    <?php } ?>

</div>

<?php require_once("includes/footer.php"); ?>
