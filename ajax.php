<?php

require_once("includes/config.php");

//Getting value of "search" variable from "script.js".
if (isset($_POST['search'])) {

//Search box value assigning to $Name variable.
    $Name = $_POST['search'];

    $querySearch = $pdo->prepare("SELECT * FROM users WHERE username LIKE '%$Name%' LIMIT 5");
    $querySearch->execute();


    while ($getSearch = $querySearch->fetch(PDO::FETCH_ASSOC)) {

?>
   <!-- Creating unordered list items.
        Calling javascript function named as "fill" found in "script.js" file.
        By passing fetched result as parameter. -->

        <a href="<?php echo $rootPath; ?>/user/<?php echo $getSearch['username']; ?>" class="text-center" style="text-decoration: none;">

            <section class="card margin-top-10">

                <span class="badge bg-light text-dark font-16 padding-15">
                    <?php echo $getSearch["username"]; ?>
                </span>

            </section>

       </a>

<?php

    }
}

?>
