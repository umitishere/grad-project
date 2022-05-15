<div class="modal fade" id="createGroup" tabindex="-1" aria-labelledby="createGroup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createGroup">Grup Oluştur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="<?php echo $rootPath; ?>/includes/group-operations.php">

            <label>Grup Adı</label>
            <input class="form-control" type="text" name="group_name" required />

            <br />

            <label>Grup Açıklaması</label>
            <textarea class="form-control" type="text" name="group_description" required></textarea>

            <section class="text-center margin-top-15">
                <button type="submit" name="create_group" class="btn btn-outline-success" type="button">
                    Oluştur
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
