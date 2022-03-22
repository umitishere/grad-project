<?php

$searchedUser = $_GET["username_to_search"];

$pageTitle = $searchedUser . " için arama sonucu | Grad Project";

require_once("includes/header.php");

$querySearchResult = $pdo->prepare("SELECT * FROM users WHERE username LIKE '%$searchedUser%' LIMIT 10");
$querySearchResult->execute();

?>

<section class="container">

    <h4 class="text-center margin-top-15"><?php echo $searchedUser; ?> için arama sonucu:</h4>
    <hr />

<?php while ($getSearchResult = $querySearchResult->fetch(PDO::FETCH_ASSOC)) { ?>

    <a href="/<?php echo $rootPath; ?>/user/<?php echo $getSearchResult['username']; ?>" style="text-decoration: none;">
        <section class="card margin-top-10">

            <span class="badge bg-light text-dark font-16 padding-15">
                <img
                    style="border-radius: 100%;"
                    src="/<?php echo $rootPath; ?>/assets/img/profile_photos/<?php echo $getSearchResult["profile_photo"]; ?>"
                    width="40px" height="40px" />
                <?php echo $getSearchResult["username"]; ?>
            </span>

        </section>
    </a>

<?php } ?>

</section>

<?php require_once("includes/footer.php"); ?>
