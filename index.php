<?php

$pageTitle = "Anasayfa | Brand";

require_once("includes/header.php");

$queryLastContents = $pdo->prepare("SELECT * FROM contents ORDER BY id DESC");
$queryLastContents->execute();

?>

<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12">

            <section class="padding-15">
                <form action="/graduation-project-web/includes/content-operations.php" method="post">

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

                    <section class="margin-top-15 card padding-15">

                        <section>
                            <?php echo nl2br($getLastContents['content_detail']); ?>
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
