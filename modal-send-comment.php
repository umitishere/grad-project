<!-- Send Comment Modal -->
<div class="modal fade" id="sendComment<?php echo $getLastContents['id']; ?>" tabindex="-1" aria-labelledby="sendComment<?php echo $getLastContents['id']; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendComment<?php echo $getLastContents['id']; ?>">Yorum Yap</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="/<?php echo $projectName; ?>/includes/content-operations.php">

            <input type="hidden" name="commented_content" value="<?php echo $getLastContents['id']; ?>" />
            <input type="hidden" name="from_where" value="home" />

            <textarea
                class="form-control"
                name="comment_detail"
                rows="2"
                maxlength="120"
            ></textarea>
            <button type="submit" name="send_comment" class="btn btn-outline-primary" type="button">
                Gönder
            </button>

        </form>

        <hr />

        <?php

        $commentedPost = $getLastContents['id'];

        $queryLastComments = $pdo->prepare("SELECT * FROM comments WHERE commented_post = '$commentedPost' ORDER BY id DESC");
        $queryLastComments->execute();

        while($getLastComments = $queryLastComments->fetch(PDO::FETCH_ASSOC)) {

            $commentSender = $getLastComments['comment_sender'];

            $queryUser = "queryUser" . $commentSender;
            $getterUser = "getUser" . $commentSender;

            $queryUser = $pdo->prepare("SELECT * FROM users WHERE id = '$commentSender'");
            $queryUser->execute();

            $getterUser = $queryUser->fetch(PDO::FETCH_ASSOC);

        ?>

        <section class="margin-top-15 card padding-15">

            <section>

                <a href="/<?php echo $projectName; ?>/user/<?php echo $getterUser['username']; ?>" class="my-links">
                    <span class="badge bg-light text-dark font-16">
                        <img
                            style="border-radius: 100%;"
                            src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getterUser["profile_photo"]; ?>"
                            width="25px" height="25px" />
                        <?php echo $getterUser["username"]; ?>
                    </span>
                </a>

                <section class="margin-top-15 font-16">
                    <?php echo nl2br($getLastComments['comment_detail']); ?>
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
<!-- Send Comment Modal -->
