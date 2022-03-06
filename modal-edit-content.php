<!-- Edit Content Modal -->
<div class="modal fade" id="editContent<?php echo $getLastContents['id']; ?>" tabindex="-1" aria-labelledby="editContent<?php echo $getLastContents['id']; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editContent<?php echo $getLastContents['id']; ?>">Gönderiyi Düzenle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="/grad-project/includes/content-operations.php">

            <input type="hidden" name="content_id" value="<?php echo $getLastContents['id']; ?>" />
            <input type="hidden" name="from_where" value="home" />

            <hr />
            <button type="submit" name="delete_content" class="btn btn-outline-danger" type="button">
                Gönderiyi Sil
            </button>

        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit Content Modal -->
