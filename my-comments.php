<?php

$pageTitle = "Yaptığım Yorumlar | Grad Project";

require_once("includes/header.php");

$queryLastComments = $pdo->prepare("SELECT * FROM comments WHERE comment_sender = $loggedUserID ORDER BY id DESC");
$queryLastComments->execute();

?>

<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12">

            <main>

                <section>

                    <h3 class="margin-top-15 text-center">Yaptığım Yorumlar</h3>
                    <hr />

                    <section class="row">

                        <?php while($getLastComments = $queryLastComments->fetch(PDO::FETCH_ASSOC)) { ?>

                            <?php $contentID = $getLastComments['commented_post']; ?>

                            <section class="col-md-6 col-sm-12">

                                <section class="margin-top-15 card padding-15">

                                    <p class="text-center"><?php echo $getLastComments['comment_detail']; ?></p>

                                    <section class="text-center">
                                        <button
                                            onclick="window.location.href='posts/<?php echo $contentID; ?>'"
                                            type="button"
                                            class="btn btn-primary"
                                        >
                                            Gönderiyi Görüntüle
                                        </button>
                                    </section>

                                </section>

                            </section>

                        <?php } ?>

                    </section>

                </section>

            </main>

        </div>

        <div class="col-md-3 col-sm-12">



        </div>

    </div>

</div>

<?php require_once("includes/footer.php"); ?>
