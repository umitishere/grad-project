<?php

require_once("VARIABLES_EVERYWHERE.php");

$pageTitle = "Anasayfa | Brand";

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
                            <section class="margin-top-15 row text-center content-icons">

                                <div class="col-3">
                                    <i class="far fa-heart"></i>
                                </div>

                                <div class="col-3">
                                    <i class="far fa-comments"></i>
                                </div>

                                <div class="col-3">
                                    <i class="far fa-share-square"></i>
                                </div>

                                <div class="col-3">
                                    <i class="far fa-plus-square"></i>
                                </div>

                            </section>
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
