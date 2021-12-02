<?php

$pageTitle = "Anasayfa | Brand";

require_once("includes/header.php");

?>

<div class="container">

    <div class="row">

        <div class="col-md-9 col-sm-12">

            <p class="text-center">Anasayfa içeriği</p>

            <a href="<?php echo substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])); ?>">aaa</a>

        </div>

        <div class="col-md-3 col-sm-12">

            <p class="text-center">Yan içerik</p>

        </div>

    </div>

</div>

<?php require_once("includes/footer.php"); ?>
