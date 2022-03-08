<!-- Report User Modal -->
<div class="modal fade" id="reportUser" tabindex="-1" aria-labelledby="reportUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title">Şikayet Et / Engelle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="post" action="/grad-project/includes/user-operations.php">

            <?php ($reportFromWhere == "Profile Page") ? (print("<input type='hidden' name='profile_username' value='$profileUsername' />")) : (print('')) ?>

            <input type="hidden" name="user_id" value="<?php echo $profileID; ?>" />
            <input type="hidden" name="from_where" value="<?php echo $reportFromWhere; ?>" />

            <hr />

            <button type="submit" name="report_user" class="btn btn-outline-danger" type="button">
                Kullanıcıyı Şikayet Et
            </button>

            <button type="submit" name="block_user" class="btn btn-outline-danger" type="button">
                Kullanıcıyı Engelle
            </button>

        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Report User Modal -->
