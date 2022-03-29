<!-- Search User Modal -->
<div class="modal fade" id="searchUser" tabindex="-1" aria-labelledby="searchUser" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchUser">Kullanıcı Ara</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

          <form method="get" action="<?php echo $rootPath; ?>/arama-sonucu">
              <div class="input-group mb-3 margin-top-15">
                  <input autofocus autocomplete="off" id="search" type="text" name="username_to_search" class="form-control" placeholder="Kullanıcı ara...">
                  <button class="btn btn-outline-dark" type="submit">
                      <i class="fas fa-search"></i>
                  </button>
              </div>

              <div id="display"></div>
          </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
<!-- Search User Modal -->
