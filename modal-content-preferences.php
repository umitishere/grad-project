<!-- Content Preferences Modal -->
<div class="modal fade" id="contentPreferences" tabindex="-1" aria-labelledby="contentPreferences" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contentPreferences">Gönderi Tercihleri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo $rootPath; ?>/includes/user-operations.php" enctype="multipart/form-data">

            <label>Görmek istediğiniz gönderiler: <b><?php echo $contentPreference; ?></b></label>
            <div class="input-group mb-3 margin-top-15">
                <select class="form-control" name="new_content_preference">
                    <option>Sadece Takip Ettiklerim</option>
                    <option>Sadece Üniversitemdekiler</option>
                    <option>Açık Olan Tüm Gönderiler</option>
                </select>
                <button type="submit" name="update_content_preferences" class="btn btn-outline-primary" type="button">
                    <i class="fas fa-save"></i>
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
<!-- Content Preferences Modal -->
