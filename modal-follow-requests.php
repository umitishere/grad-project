<!-- Show Follow Requests Modal -->
<div class="modal fade" id="followRequests" tabindex="-1" aria-labelledby="followRequests" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="showFollowers">Takip İstekleri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <?php

                while($getFollowRequests = $queryFollowRequests->fetch(PDO::FETCH_ASSOC)) {

                ?>

                <section class="padding-15 text-center row">

                    <!-- FOLLOW REQUEST DETAILS -->
                    <div class="col-8 text-center">
                      <div class="text-center">
                          <a href="/user/<?php echo $getFollowRequests['username']; ?>" class="my-links">
                              <span class="badge bg-light text-dark font-16">
                                  <img
                                      style="border-radius: 100%;"
                                      src="/assets/img/profile_photos/<?php echo $getFollowRequests["profile_photo"]; ?>"
                                      width="40px" height="40px" />
                                  <?php echo $getFollowRequests["username"]; ?>
                              </span>
                          </a>
                      </div>
                    </div>

                    <div class="col-4 text-center">
                        <form action="/includes/follower-operations.php" method="post">

                            <input type="hidden" name="request_sender" value="<?php echo $getFollowRequests['user_id'] ?>" />

                            <button type="submit" name="accept_follow_request" class="btn btn-success">
                                <i class="fas fa-check"></i>
                            </button>

                            <button type="submit" name="decline_follow_request" class="btn btn-danger">
                                <i class="fas fa-times"></i>
                            </button>

                        </form>
                    </div>


                      <!-- /FOLLOW REQUEST DETAILS -->

                </section>

                <?php } ?>



            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>

        </div>

    </div>

</div>
<!-- /Show Follow Requests Modal -->
