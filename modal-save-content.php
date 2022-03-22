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

                            <form action="<?php echo $rootPath; ?>/includes/content-operations.php" method="post">

                                <input type="hidden" name="saved_content_id" value="<?php echo $contentID; ?>" />
                                <input type="hidden" name="list_name" value="<?php echo $getSavedLists["list_name"]; ?>" />
                                <input type="hidden" name="from_where" value="<?php echo $reportFromWhere; ?>" />
                                <input type="hidden" name="profile_username" value="<?php echo $getLastContents['username']; ?>" />

                                <button type="submit" name="save_to_existing_list" class="btn btn-outline-primary" type="button">
                                    <i class="far fa-plus-square"></i>
                                </button>

                            </form>

                        </section>

                    </section>

                </section>

            <?php } ?>

            <hr />
            <p class="text-center font-16 margin-top-15">Yeni Liste Oluştur ve Kaydet</p>

            <form action="<?php echo $rootPath; ?>/includes/content-operations.php" method="post">

                <input type="hidden" name="saved_content_id" value="<?php echo $contentID; ?>" />
                <input type="hidden" name="from_where" value="<?php echo $reportFromWhere; ?>" />
                <input type="hidden" name="profile_username" value="<?php echo $getLastContents['username']; ?>" />

                <div class="input-group">
                    <input
                        name="list_name"
                        type="text"
                        class="form-control"
                    />
                    <button class="btn btn-outline-primary" type="submit" name="save_to_new_list">
                        <i class="far fa-plus-square"></i>
                    </button>
                </div>

            </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Forward Content Modal -->
