<?php

require_once("config.php");

if (!isset($_SESSION)) {
    session_start();
}

$sessionID = $_SESSION["id"];

if (isset($_POST['create_group'])) {

    $group_name = htmlspecialchars($_POST["group_name"], ENT_QUOTES);
    $group_description = htmlspecialchars($_POST["group_description"], ENT_QUOTES);

    $contentData = [
        ":group_creator_id"=>$sessionID,
        ":group_name"=>$group_name,
        ":group_description"=>$group_description
    ];

    $query = "INSERT INTO `groups`
    (
        `group_creator_id`,
        `group_name`,
        `group_description`
    )
    VALUES
    (
        :group_creator_id,
        :group_name,
        :group_description
    )";

    $pdoResult = $pdo->prepare($query);
    $pdoExecute = $pdoResult->execute($contentData);

    header("Location: ../gruplar");

}

?>
