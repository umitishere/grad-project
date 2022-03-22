<?php

$contentID = $getLastContents['id'];

?>

<!-- Forward Content Modal -->
<div class="modal fade" id="saveContent<?php echo $contentID; ?>" tabindex="-1" aria-labelledby="saveContent<?php echo $contentID; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="saveContent<?php echo $contentID; ?>">Gönderiyi Kaydet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <?php

        $sessionID = $_SESSION["id"];

        $sqlStatement = "SELECT * FROM saved_contents
                        WHERE saver_id = '$sessionID'
                        GROUP BY list_name";

        $querySavedLists = $pdo->prepare($sqlStatement);
        $querySavedLists->execute();

        ?>

            <input type="hidden" name="content_id" value="<?php echo $contentID; ?>" />

            <h3 class="text-center margin-top-10">Listelerim</h3>
            <hr />

            <?php while ($getSavedLists = $querySavedLists->fetch(PDO::FETCH_ASSOC)) { ?>

                <section class="card padding-15 margin-top-10">

                    <section class="row">

                        <section class="col-10">
                            <span class="badge bg-light text-dark font-16">
                                <?php echo $getSavedLists["list_name"]; ?>
                            </span>
                        </section>

                        <section class="col-2">

                            <form action="/grad-project/includes/send-message.php" method="post">

                                <input type="hidden" name="message_getter" value="<?php // echo $getFollowedByMe['followed_id']; ?>" />
                                <input type="hidden" name="message_detail" value="<?php echo $contentID; ?>" />
                                <input type="hidden" name="isThisPost" value="1" />

                                <button type="submit" name="send_message" class="btn btn-outline-primary" type="button">
                                    <i class="fas fa-paper-plane"></i>
                                </button>

                            </form>

                        </section>

                    </section>

                </section>

            <?php } ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Forward Content Modal -->
