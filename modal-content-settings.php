<!-- Content Settings Modal -->
<div class="modal fade" id="contentSettings<?php echo $contentID; ?>" tabindex="-1" aria-labelledby="contentSettings<?php echo $contentID; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="contentSettings<?php echo $contentID; ?>">Gönderi Ayarları</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="<?php echo $rootPath; ?>/includes/content-operations.php">

            <?php ($reportFromWhere == "Profile Page") ? (print("<input type='hidden' name='profile_username' value='$profileUsername' />")) : (print('')) ?>

            <input type="hidden" name="content_id" value="<?php echo $contentID; ?>" />
            <input type="hidden" name="from_where" value="<?php echo $reportFromWhere; ?>" />

            <button type="submit" name="report_content" class="btn btn-outline-danger" type="button">
                Gönderiyi Şikayet Et
            </button>

            <hr />

            <section class="margin-top-15">

                <p class="font-12">Anasayfada bu kullanıcının gönderileri gösterilmez fakat profilini ziyaret edince görülebilir.</p>

                <button type="submit" name="mute_user" class="btn btn-outline-warning" type="button">
                    Kullanıcıyı Sessize Al
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
<!-- Content Settings Modal -->
