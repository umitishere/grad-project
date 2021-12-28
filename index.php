<?php

require_once("VARIABLES_EVERYWHERE.php");

$pageTitle = "Anasayfa | Grad Project";

require_once("includes/header.php");

$queryLastContents = $pdo->prepare("SELECT * FROM contents ORDER BY id DESC");
$queryLastContents->execute();

?>

<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12">

            <section class="padding-15 content-share margin-top-15">
                <form action="/<?php echo $projectName; ?>/includes/content-operations.php" method="post">

                    <textarea
                        placeholder="Ne düşünüyorsun?"
                        class="form-control"
                        name="content_detail"
                        rows="2"
                        maxlength="450"
                    ></textarea>

                    <section class="text-center margin-top-15">
                        <button type="submit" name="create_content" class="btn btn-primary btn-lg">
                            <i class="far fa-paper-plane"></i>
                        </button>
                    </section>

                </form>
            </section>

            <main>

                <section>

                <?php while($getLastContents = $queryLastContents->fetch(PDO::FETCH_ASSOC)) { ?>

                    <?php

                    $contentID = $getLastContents['id'];
                    $publisherID = $getLastContents['publisher_id'];

                    $queryName = "queryPublisher" . $contentID;
                    $getterName = "getPublisher" . $contentID;

                    $queryLikesName = "queryLikes" . $contentID;
                    $getLikesName = "getLikes" . $contentID;

                    $queryName = $pdo->prepare("SELECT * FROM users WHERE id = $publisherID");
                    $queryName->execute();

                    $getterName = $queryName->fetch(PDO::FETCH_ASSOC);

                    ?>

                    <section class="margin-top-15 card padding-15">

                        <section>
                            <a href="/<?php echo $projectName; ?>/user/<?php echo $getterName['username']; ?>" class="my-links">
                                <span class="badge bg-light text-dark font-16">
                                    <img
                                        style="border-radius: 100%;"
                                        src="/<?php echo $projectName; ?>/assets/img/profile_photos/<?php echo $getterName["profile_photo"]; ?>"
                                        width="25px" height="25px" />
                                    <?php echo $getterName["username"]; ?>
                                </span>
                            </a>
                            <section class="margin-top-15">
                                <?php echo nl2br($getLastContents['content_detail']); ?>
                            </section>

                            <form action="/<?php echo $projectName; ?>/includes/content-operations.php" method="post">

                                <input type="hidden" name="liked_content" value="<?php echo $getLastContents['id']; ?>" />
                                <input type="hidden" name="from_where" value="home" />

                                <section class="margin-top-15 row text-center content-icons">

                                    <?php

                                        $likedContent = $getLastContents['id'];

                                        $queryLikesName = $pdo->prepare(
                                            "SELECT * FROM liked_contents
                                            WHERE liked_content = $likedContent
                                            AND who_liked = $loggedUserID
                                        ");
                                        $queryLikesName->execute();

                                    ?>

                                    <div class="col-3">

                                        <?php if ($queryLikesName->rowCount() == 1) { ?>

                                        <button type="submit" name="dislike_content" class="content-button">
                                            <i class="fas fa-heart"></i>
                                        </button>

                                        <?php } else { ?>

                                        <button type="submit" name="like_content" class="content-button">
                                            <i class="far fa-heart"></i>
                                        </button>

                                        <?php } ?>

                                    </div>

                                    <?php include("modal-send-comment.php"); ?>

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

                                    <div class="col-3">
                                        <i class="far fa-share-square"></i>
                                    </div>

                                    <div class="col-3">
                                        <i class="far fa-plus-square"></i>
                                    </div>

                                </section>

                            </form>
                        </section>

                    </section>

                <?php } ?>

                </section>

            </main>

        </div>

        <div class="col-md-3 col-sm-12">



        </div>

    </div>

</div>

<?php require_once("includes/footer.php"); ?>
