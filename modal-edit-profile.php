<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="editProfile" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfile">Profili Düzenle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo $rootPath; ?>/includes/user-operations.php" enctype="multipart/form-data">

            <label for="update-profile-photo">Profil Fotoğrafı</label>
            <div class="input-group">
                <input
                    name="new_profile_photo"
                    type="file"
                    class="form-control"
                    id="update-profile-photo"
                    aria-describedby="inputGroupFileAddon04"
                    aria-label="Upload"
                />
                <button class="btn btn-outline-success" type="submit" name="update_profile_photo"><i class="fas fa-save"></i></button>
            </div>

            <br />

            <label for="update-username">Kullanıcı Adı</label>
            <div class="input-group mb-3">
                <input
                    class="form-control"
                    type="text"
                    value="<?php echo $getProfileInfo['username']; ?>"
                    aria-describedby="button-username"
                    id="update-username"
                    name="new_username"
                    maxlength="16"
                    required
                />
                <button type="submit" name="update_username" class="btn btn-outline-success" type="button">
                    <i class="fas fa-save"></i>
                </button>
            </div>
            <p class="text-danger"><?php echo $usernameChangeError; ?></p>

            <label for="update-username">Biyografi</label>
            <div class="input-group mb-3">
                <input
                    class="form-control"
                    type="text"
                    value="<?php echo trim($getProfileInfo['biography']); ?>"
                    aria-describedby="button-username"
                    id="update-biography"
                    name="new_biography"
                    maxlength="180"
                />
                <button type="submit" name="update_biography" class="btn btn-outline-success" type="button">
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
<!-- Edit Profile Modal -->
