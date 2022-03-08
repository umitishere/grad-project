<!-- CONTENT CARD SECTION -->
<section class="margin-top-15 card padding-15">

    <!-- CONTENT DETAILS SECTION -->
    <section>

        <a href="/grad-project/user/<?php echo $getLastContents['username']; ?>" class="my-links">
            <span class="badge bg-light text-dark font-16">
                <img
                    style="border-radius: 100%;"
                    src="/grad-project/assets/img/profile_photos/<?php echo $getLastContents["profile_photo"]; ?>"
                    width="25px" height="25px" />
                <?php echo $getLastContents["username"]; ?>
            </span>
        </a>

        <span style="float: right; clear: right;">
            <?php if ($getLastContents['publisher_id'] == $loggedUserID) { ?>
            <button
                type="button"
                class="content-button"
                data-bs-toggle="modal"
                data-bs-target="#editContent<?php echo $getLastContents['id']; ?>"
            >
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <?php } else { ?>
                <button
                    type="button"
                    class="content-button"
                    data-bs-toggle="modal"
                    data-bs-target="#contentSettings<?php echo $getLastContents['id']; ?>"
                >
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            <?php } ?>
        </span>

        <section class="margin-top-15">
            <a href="/grad-project/posts/<?php echo $getLastContents['id']; ?>" style="color: black; text-decoration: none;">
                <?php echo nl2br($getLastContents['content_detail']); ?>
            </a>
        </section>

        <form action="/grad-project/includes/content-operations.php" method="post">

            <input type="hidden" name="liked_content" value="<?php echo $getLastContents['id']; ?>" />
            <input type="hidden" name="liked_from_where" value="<?php echo $likedFromWhere; ?>" />

            <!-- /CONTENT ACTION ICONS SECTION -->
            <section class="margin-top-15 row text-center content-icons">

            <?php

            $likedContent = $getLastContents['id'];

            $queryMyLikes = $pdo->prepare(
                "SELECT * FROM liked_contents
                WHERE liked_content = $likedContent
                AND who_liked = $loggedUserID
            ");
            $queryMyLikes->execute();

            ?>

                <!-- LIKE BUTTON -->
                <div class="col-3">

                <?php

                $queryTotalLikes = $pdo->prepare(
                    "SELECT * FROM liked_contents WHERE liked_content = $likedContent"
                );
                $queryTotalLikes->execute();

                $likesCount = $queryTotalLikes->rowCount();

                if ($queryMyLikes->rowCount() == 1) {

                ?>

                    <button type="submit" name="dislike_content" class="content-button">
                        <i class="fas fa-heart"></i>
                        <span class="font-20"><?php ($canSeeLikes) ? (print($likesCount)) : ("") ?></span>
                    </button>

                <?php } else { // WHEN USER DID NOT LIKE ?>

                    <button type="submit" name="like_content" class="content-button">
                        <i class="far fa-heart"></i>
                        <span class="font-20"><?php ($canSeeLikes) ? (print($likesCount)) : ("") ?></span>
                    </button>

                <?php } ?>

                </div>
                <!-- /LIKE BUTTON -->

                <?php

                include("modal-send-comment.php");

                ($getLastContents['publisher_id'] == $loggedUserID) ? include("modal-edit-content.php") : include("modal-content-settings.php")

                ?>

                <!-- COMMENT BUTTON -->
                <div class="col-3">

                    <button
                        type="button"
                        name="like_content"
                        class="content-button"
                        data-bs-toggle="modal"
                        data-bs-target="#sendComment<?php echo $getLastContents['id']; ?>"
                    >
                        <i class="far fa-comments"></i>
                    </button>

                </div>
                <!-- /COMMENT BUTTON -->

                <!-- FORWARD CONTENT BUTTON -->
                <div class="col-3">

                    <button
                        type="button"
                        class="content-button"
                        data-bs-toggle="modal"
                        data-bs-target="#forwardContent<?php echo $getLastContents['id']; ?>"
                    >
                        <i class="far fa-share-square"></i>
                    </button>

                    <?php

                    $forwardFromWhere = "Home";

                    include("modal-forward-content.php");

                    ?>

                </div>
                <!-- /FORWARD CONTENT BUTTON -->

                <!-- SAVE CONTENT BUTTON -->
                <div class="col-3">
                    <i class="far fa-plus-square"></i>
                </div>
                <!-- /SAVE CONTENT BUTTON -->

            </section>
            <!-- /CONTENT ACTION ICONS SECTION -->

        </form>
    </section>
    <!-- /CONTENT DETAILS SECTION -->

</section>
<!-- /CONTENT CARD SECTION -->
