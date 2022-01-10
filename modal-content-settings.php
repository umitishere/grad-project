<!-- Send Comment Modal -->
<div class="modal fade" id="contentSettings<?php echo $getLastContents['id']; ?>" tabindex="-1" aria-labelledby="contentSettings<?php echo $getLastContents['id']; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="/<?php echo $projectName; ?>/includes/content-operations.php">

            <input type="hidden" name="content_id" value="<?php echo $getLastContents['id']; ?>" />
            <input type="hidden" name="from_where" value="home" />

            <hr />
            <button type="submit" name="report_content" class="btn btn-outline-danger" type="button">
                Gönderiyi Şikayet Et
            </button>

        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Send Comment Modal -->
