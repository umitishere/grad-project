<!-- CONTENT CARD SECTION -->
<section class="margin-top-15 card padding-15">

    <!-- CONTENT DETAILS SECTION -->
    <section>

        <a href="<?php echo $rootPath; ?>/user/<?php echo $getLastContents['username']; ?>" class="my-links">
            <span class="badge bg-light text-dark font-16">
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
            <a href="<?php echo $rootPath; ?>/posts/<?php echo $getLastContents['id']; ?>" style="color: black; text-decoration: none;">
                <?php echo nl2br($getLastContents['content_detail']); ?>
            </a>
        </section>

        <form action="<?php echo $rootPath; ?>/includes/content-operations.php" method="post">

            <input type="hidden" name="liked_content" value="<?php echo $getLastContents['id']; ?>" />
            <input type="hidden" name="liked_from_where" value="<?php echo $likedFromWhere; ?>" />
            <input type="hidden" name="profile_username" value="<?php echo $getLastContents['username']; ?>" />

            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) { ?>

            <!-- /CONTENT ACTION ICONS SECTION IF LOGGED IN -->
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

                    <?php

                    $savedContent = $getLastContents['id'];

                    $queryIsSaved = $pdo->prepare(
                        "SELECT * FROM saved_contents
                        WHERE saved_content_id = $savedContent
                        AND saver_id = $loggedUserID
                    ");
                    $queryIsSaved->execute();

                    if ($queryIsSaved->rowCount() == 1) {

                    ?>

                    <button
                        type="submit"
                        class="content-button"
                        name="remove_from_saved_contents"
                    >
                        <i class="fas fa-plus-square"></i>
                    </button>

<?php               } else { ?>

                    <button
                        type="button"
                        class="content-button"
                        data-bs-toggle="modal"
                        data-bs-target="#saveContent<?php echo $getLastContents['id']; ?>"
                    >
                        <i class="far fa-plus-square"></i>
                    </button>

                    <?php

                    }

                    $forwardFromWhere = "Home";

                    include("modal-save-content.php");

                    ?>

                </div>
                <!-- /SAVE CONTENT BUTTON -->

            </section>
            <!-- /CONTENT ACTION ICONS SECTION IF LOGGED IN -->

        <?php } else { ?>

            <!-- /CONTENT ACTION ICONS SECTION IF NOT LOGGED IN -->
            <section class="margin-top-15 row text-center content-icons">

                <!-- LIKE BUTTON -->
                <div class="col-3">

                    <button onclick="window.location.href='giris-yap';" type="button" name="like_content" class="content-button">
                        <i class="far fa-heart"></i>
                        <span class="font-20"></span>
                    </button>

                </div>
                <!-- /LIKE BUTTON -->

                <!-- COMMENT BUTTON -->
                <div class="col-3">

                    <button
                        onclick="window.location.href='giris-yap';"
                        type="button"
                        name="like_content"
                        class="content-button"
                    >
                        <i class="far fa-comments"></i>
                    </button>

                </div>
                <!-- /COMMENT BUTTON -->

                <!-- FORWARD CONTENT BUTTON -->
                <div class="col-3">

                    <button
                        onclick="window.location.href='giris-yap';"
                        type="button"
                        class="content-button"
                    >
                        <i class="far fa-share-square"></i>
                    </button>

                </div>
                <!-- /FORWARD CONTENT BUTTON -->

                <!-- SAVE CONTENT BUTTON -->
                <div class="col-3">

                    <button
                        onclick="window.location.href='giris-yap';"
                        type="button"
                        class="content-button"
                    >
                        <i class="far fa-plus-square"></i>
                    </button>

                </div>
                <!-- /SAVE CONTENT BUTTON -->



            </section>
            <!-- /CONTENT ACTION ICONS SECTION IF NOT LOGGED IN -->

        <?php } ?>

        </form>
    </section>
    <!-- /CONTENT DETAILS SECTION -->

</section>
<!-- /CONTENT CARD SECTION -->
