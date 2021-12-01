<?php

require_once("config.php");

if (!isset($_SESSION)) {
    session_start();
}

$sessionID = $_SESSION["id"];
$username = $_SESSION["username"];

if (isset($_POST["update_username"])) {

    $newUsername = $_POST["new_username"];

    $query = $pdo->prepare("UPDATE users SET username = '$newUsername' WHERE id = '$sessionID'");

    if (strlen(trim($newUsername)) < 17 && strlen(trim($newUsername)) > 5) {
        $queryExecute = $query->execute();
    }

    $_SESION["username"] = $newUsername;

    header("Location: ../$newUsername");

}

if (isset($_POST["update_biography"])) {

    $newBiography = $_POST["new_biography"];

    $query = $pdo->prepare("UPDATE users SET biography = '$newBiography' WHERE id = '$sessionID'");

    if (strlen(trim($newBiography)) < 181) {
        $queryExeute = $query->execute();
    }

    header("Location: ../$username");

}

if (isset($_POST["update_profile_photo"])) {

    // Check Image Detail And Insert Data
    $target_dir = "../assets/img/profile_photos/";
    $randomNumber = rand(1, 9999999);
    $target_file = $target_dir . $randomNumber . basename($_FILES["new_profile_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Üzgünüz, bu dosya zaten mevcut.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["new_profile_photo"]["size"] > 9000000) {
        echo "Üzgünüz, görselin boyutu en fazla 9 MB olmalıdır.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Üzgünüz, sadece JPG, JPEG, PNG & GIF türünde dosyaları yükleyebilirsiniz.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Üzgünüz, fotoğrafınızı yükleyemedik.";
    // if everything is ok, try to upload file
    } else {

        if (move_uploaded_file($_FILES["new_profile_photo"]["tmp_name"], $target_file)) {

            $imgName = $randomNumber . basename($_FILES["new_profile_photo"]["name"]);

            $query = $pdo->prepare("UPDATE users SET profile_photo = '$imgName' WHERE id = '$sessionID'");

            $queryExeute = $query->execute();

        } else {
            echo "Fotoğraf yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.";
        }
    }

    header("Location: ../$username");

}

?>
