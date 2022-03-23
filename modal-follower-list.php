<!-- Show Followers Modal -->
<div class="modal fade" id="showFollowers" tabindex="-1" aria-labelledby="showFollowers" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="showFollowers">Takipçiler</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <section class="padding-15 text-center row">

                <?php

                while($getFollowers = $queryFollowers->fetch(PDO::FETCH_ASSOC)) {

                    $followID = $getFollowers['id'];
                    $followerID = $getFollowers['follower_id'];

                    $queryNameFollower = "queryFollowerList" . $followID;
                    $getterNameFollower = "getFollowerList" . $followID;

                    $queryNameFollower = $pdo->prepare("SELECT * FROM users WHERE id = '$followerID'");
                    $queryNameFollower->execute();

                    $getterNameFollower = $queryNameFollower->fetch(PDO::FETCH_ASSOC);

                ?>

                      <!-- FOLLOWER DETAILS -->
                      <div class="col-6 text-center">
                          <div class="text-center margin-top-15">
                              <a href="<?php echo $rootPath; ?>/user/<?php echo $getterNameFollower['username']; ?>" class="my-links">
                                  <span class="badge bg-light text-dark font-16">
                                      <?php echo $getterNameFollower["username"]; ?>
                                  </span>
                              </a>
                          </div>
                      </div>
                      <!-- /FOLLOWER DETAILS -->

                <?php } ?>

                </section>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>

        </div>

    </div>

</div>
<!-- /Show Followers Modal -->


<!-- Show Followings Modal -->
<div class="modal fade" id="showFollowings" tabindex="-1" aria-labelledby="showFollowings" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="showFollowings">Takip Edilenler</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <section class="padding-15 text-center row">

                <?php

                while($getFollowings = $queryFollowing->fetch(PDO::FETCH_ASSOC)) {

                    $followID = $getFollowings['id'];
                    $followingID = $getFollowings['followed_id'];

                    $queryNameFollowing = "queryFollowingList" . $followID;
                    $getterNameFollowing = "getFollowingList" . $followID;

                    $queryNameFollowing = $pdo->prepare("SELECT * FROM users WHERE id = '$followingID'");
                    $queryNameFollowing->execute();

                    $getterNameFollowing = $queryNameFollowing->fetch(PDO::FETCH_ASSOC);

              ?>

                      <!-- FOLLOWING DETAILS -->
                      <div class="col-6 text-center">
                          <div class="text-center margin-top-15">
                              <a href="<?php echo $rootPath; ?>/user/<?php echo $getterNameFollowing['username']; ?>" class="my-links margin-top-15">
                                  <span class="badge bg-light text-dark font-16">
                                      <?php echo $getterNameFollowing["username"]; ?>
                                  </span>
                              </a>
                          </div>
                      </div>
                      <!-- /FOLLOWING DETAILS -->


                <?php } ?>

                </section>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>

        </div>

    </div>

</div>
<!-- /Show Followings Modal -->
