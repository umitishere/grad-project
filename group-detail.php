<?php

$pageTitle = "Grubu Görüntüle | Grad Project";

if (!isset($_SESSION)) {
    session_start();
}

$groupID = "";

if (isset($_GET)) {
    $groupID = $_GET['groupID'];
}

require_once("includes/header.php");

$queryGroup = $pdo->prepare("SELECT * FROM groups WHERE group_id = '$groupID'");
$queryGroup->execute();

$getGroup = $queryGroup->fetch(PDO::FETCH_ASSOC);

$numberOfMembers = 3;

?>

<div class="container">

    <div class="row margin-top-15">

        <div class="col-md-3 col-sm-12">

            <!-- GROUP INFORMATION CARD -->
            <div class="card padding-15 margin-top-15">

                <p class="profileInfoText margin-top-15"><?php echo $getGroup["group_name"]; ?></p>

                <p class="margin-top-15"><?php echo $getGroup["group_description"]; ?></p>

                <!-- NUMBER OF MEMBERS -->
                <section class="text-center padding-15">

                    <div class="text-center" data-bs-toggle="modal" data-bs-target="#showMembers">
                        <div><b><?php echo $numberOfMembers; ?></b></div>
                        <div>Üye</div>
                    </div>

                </section>
                <!-- /NUMBER OF MEMBERS -->

            </div>
            <!-- /GROUP INFORMATION CARD -->

        </div>

        <div class="col-md-9 col-sm-12">

        </div>

    </div>
    <!-- END OF ROW -->

</div>
