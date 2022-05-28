<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("Location: giris-yap");
}

$pageTitle = "Gruplar | Grad Project";

require_once("includes/header.php");

$queryProfileStatus = $pdo->prepare("SELECT * FROM users WHERE id = '$loggedUserID'");
$queryProfileStatus->execute();

$getProfileStatus = $queryProfileStatus->fetch(PDO::FETCH_ASSOC);

$queryMyGroups = $pdo->prepare(
    "SELECT groups.*, group_members.*
    FROM groups
    LEFT JOIN group_members
    ON groups.group_id = group_members.joined_group_id
    WHERE groups.group_creator_id = '$loggedUserID' OR group_members.joined_user_id = '$loggedUserID'"
);
$queryMyGroups->execute();

?>

<div class="container">

    <h3 class="text-center margin-top-15">Gruplar</h3>
    <hr />

    <div class="margin-top-15">

        <form method="get" action="<?php echo $rootPath; ?>/arama-sonucu">
            <div class="input-group mb-3 margin-top-15">
                <input
                    autocomplete="off"
                    id="search_group"
                    type="text"
                    name="username_to_search"
                    class="form-control"
                    placeholder="Grup ara..."
                />
                <button class="btn btn-outline-dark" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <div id="display_group_results"></div>
        </form>

    </div>

    <section class="text-center margin-top-15">
        <button class="btn btn-outline-success" data-bs-toggle='modal' data-bs-target='#createGroup'>
            <i class="fas fa-plus"></i> Grup Oluştur
        </button>
    </section>

    <section class="margin-top-15 text-center">

<?php while($getMyGroups = $queryMyGroups->fetch(PDO::FETCH_ASSOC)) { ?>

        <section class="card padding-15 margin-top-15">

            <h3 class="text-center margin-top-15"><?php echo $getMyGroups['group_name']; ?></h3>

            <section class="margin-top-15 text-center">
                <a href="grup?groupID=<?php echo $getMyGroups['group_id']; ?>">
                    <button class="btn btn-outline-primary">Grubu Görüntüle</button>
                </a>
            </section>

        </section>

<?php } ?>

    </section>

<?php require_once("modal-create-group.php"); ?>

</div>

<?php require_once("includes/footer.php"); ?>
