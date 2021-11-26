<?php

if (!isset($_SESSION)) {
    session_start();
}

$myProfile = false;
$profileUsername = $_GET["username"];
$errorMessage = "";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    if ($profileUsername == $_SESSION["username"]) {
        $myProfile = true;
    } else {
        $myProfile = false;
    }
}

$pageTitle = $profileUsername . " profili | Brand";

require_once("includes/header.php");

$chekIfUserExists = "SELECT id FROM users WHERE username = :username";

if($stmt = $pdo->prepare($chekIfUserExists)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

    // Set parameters
    $param_username = trim($profileUsername);

    // Attempt to execute the prepared statement
    if($stmt->execute()){
        if ($stmt->rowCount() == 1) {
            $errorMessage = "";
        } else{
            $errorMessage = "Aradığınız kullanıcı bulunamadı.";
        }
    }

}

?>

<div class="container">

    <?php
        if (!empty($errorMessage)) {
            echo "<h1 class='text-center margin-top-15'>Aradığınız kullanıcı bulunamadı.</h1>";
            exit;
        }
    ?>

    <div class="row">

        <div class="col-md-9 col-sm-12">

            <p class="text-center">Profil içeriği</p>

        </div>

        <div class="col-md-3 col-sm-12">

            <p class="text-center">Yan içerik</p>

        </div>

    </div>

</div>

<?php require_once("includes/footer.php"); ?>
