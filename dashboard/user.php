<?php

error_reporting(0);
session_start();

require "../include/logic.php";

//Check if user is logged in
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: login');
    exit;
}

//Assigns user id to a varible
$uid = $_SESSION['user_id'];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['ranks'] < 90){
        header('Location: ' . $url);
        exit;
    }

    $userID = $_GET['p'];

    $userPull = $pdo->prepare("SELECT * FROM user WHERE id = $userID");
    $userPull->execute();
    $userP = $userPull->fetch(PDO::FETCH_ASSOC);

    $rankid = $userP['ranks'];
    $rankPull = $pdo->prepare("SELECT * FROM ranks WHERE id = '$rankid'");
    $rankPull ->execute();
    $rank = $rankPull ->fetch(PDO::FETCH_ASSOC);

    $userProfilePull = $pdo->prepare("SELECT * FROM user_profile WHERE uid = $userID");
    $userProfilePull->execute();
    $userProfile = $userProfilePull->fetch(PDO::FETCH_ASSOC);

    $userProfilePullLogged = $pdo->prepare("SELECT * FROM user_profile WHERE uid = $uid");
    $userProfilePullLogged->execute();
    $userProfileLogged = $userProfilePullLogged->fetch(PDO::FETCH_ASSOC);

    $userAwardsPull = $pdo->prepare("SELECT * FROM user_awards WHERE uid = $userID");
    $userAwardsPull->execute();
    $userAwards = $userAwardsPull->fetchAll(PDO::FETCH_ASSOC);

}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <meta name="description" content="We help Minecraft players find eye pleasing palettes to build with as well as create a place to connect with submitting your own palettes and monthly building contest!">
    <meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative">
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <title>Block Palettes - Minecraft Building Inspiration Through Blocks</title>
    <!-- Custom fonts for this template-->
    <link href="<?=$url?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="<?=$url?>css/main.css">
    <link href="<?=$url?>css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81969207-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-81969207-1');
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <style>
        /* HIDE RADIO */
        [type=radio] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        [type=radio] + img {
            cursor: pointer;
        }

        /* CHECKED STYLES */
        [type=radio]:checked + img {
            outline: 2px solid #f00;
        }
    </style>

</head>
<body id="page-top">
<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=$url?>dashboard">
            <img src="<?=$url?>img/logotestwhite.png" width="100%">
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="<?=$url?>dashboard">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Site Function
        </div>


        <li class="nav-item">
            <a class="nav-link" href="../palettes">
                <i class="fas fa-fw fa-th-large"></i>
                <span>Palettes</span></a>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Community
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item active">
            <a class="nav-link" href="../users">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span></a>
        </li>

        <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="charts.html">
                <i class="fas fa-fw fa-trophy"></i>
                <span>Contests</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>



    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column bg-white">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top" style="border-bottom: #ededed solid 1px">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if($userProfileLogged['minecraft_ign'] == null) { ?>
                                <img src="<?=$url?>img/default.jpg" class="profile-pic">
                            <?php } else { ?>
                                <img src="<?=$url?>include/face.php?u=<?=$userProfileLogged['minecraft_ign']?>&s=48&v=front" class="profile-pic">
                            <?php } ?>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 medium-title"><?=ucwords($userP['username'])?></h1>
                </div>


                <!-- Content Row -->

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-dark">Profile Card</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="row">
                                    <?php if($userP['status'] == 1){ ?>
                                        <div class="status-bar" style="background:#95a5a6">
                                            Muted User
                                        </div>
                                    <?php } else if($userP['status'] == 2){ ?>
                                        <div class="status-bar">
                                            Suspended User
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="row">
                                    <?php if($userProfile['minecraft_ign'] == null) { ?>
                                        <img src="<?=$url?>img/default.jpg" class="profile-pic-large" style="margin-left: 10px">
                                    <?php } else { ?>
                                        <img src="<?=$url?>include/face.php?u=<?=$userProfile['minecraft_ign']?>&s=48&v=front" class="profile-pic-large" style="margin-left: 10px">
                                    <?php } ?>
                                    <div style="margin-left:10px">
                                        <p class="small-title" style="margin-bottom: 0; padding-top: 6px"><?=ucwords($userP['username'])?></p>
                                        <div class="role-pill" style="background:<?=$rank['rank_color']?>"><?=ucwords($rank['rank_name'])?></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div style="padding-top: 25px;margin-left:10px">
                                        <b>Bio</b><br>
                                        <?php if($userProfile['bio'] == null){ ?>
                                            <i>No information given.</i>
                                        <?php } else { ?>
                                            <i><?=$userProfile['bio']?></i>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div style="padding-top: 25px; margin-left:10px">
                                        <b>Awards</b><br>
                                        <?php if($userAwards == null){ ?>
                                            <i>None to display... For now.</i>
                                        <?php } else { ?>
                                            <?php foreach($userAwards as $a): ?>
                                                <?php
                                                $awardID = $a['award_id'];
                                                $awardsPull = $pdo->prepare("SELECT * FROM awards WHERE id = $awardID");
                                                $awardsPull->execute();
                                                $awards = $awardsPull->fetch(PDO::FETCH_ASSOC);

                                                ?>
                                                <img src="<?=$url?>img/awards/<?=$awards['award_icon']?>" data-toggle="tooltip" data-placement="bottom" title="<?=$awards['award_bio']?>">
                                            <?php endforeach; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Area Chart -->
                    <div class="col-xl-8">
                        <div class="card mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-dark">Update Info</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        Update user rank
                                        <form action="user" method="post">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <input type="hidden" name="id" value="<?=$userID?>">
                                                        <select class="form-control" id="rank" name="rank">
                                                            <option value="0" <?php if($rank['rank_name'] == "user"){ echo "selected"; } ?>>User</option>
                                                            <option value="5" <?php if($rank['rank_name'] == "builder"){ echo "selected"; } ?>>Builder</option>
                                                            <option value="50" <?php if($rank['rank_name'] == "contributor"){ echo "selected"; } ?>>Contributor</option>
                                                            <option value="99" <?php if($rank['rank_name'] == "staff"){ echo "selected"; } ?>>Staff</option>
                                                            <option value="100" <?php if($rank['rank_name'] == "developer"){ echo "selected"; } ?>>Developer</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="submit" class="btn-theme btn btn-block" name="updateRank" id="name" value="Update" style="background-color: black!important">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        Give award
                                        <form action="user" method="post">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <?php
                                                        $awardsPull = $pdo->prepare("SELECT * FROM awards");
                                                        $awardsPull->execute();
                                                        $awards = $awardsPull->fetchAll(PDO::FETCH_ASSOC);
                                                        ?>
                                                        <input type="hidden" name="id" value="<?=$userID?>">
                                                        <input type="hidden" name="email" value="<?=$userP['email']?>">
                                                        <input type="hidden" name="username" value="<?=$userP['username']?>">
                                                        <?php foreach ($awards as $awa): ?>
                                                            <label>
                                                                <input type="radio" name="award" value="<?=$awa['id']?>" required>
                                                                <img src="<?=$url?>img/awards/<?=$awa['award_icon']?>">
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="submit" class="btn-theme btn btn-block" name="giveAward" id="name" value="Update" style="background-color: black!important">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                        Advanced User Management
                                        <form action="user" method="post">
                                            <input type="hidden" name="id" value="<?=$userID?>">
                                            <div class="form-group">
                                                <div class="row">
                                                    <?php if($userP['status'] == 1){ ?>
                                                        <div class="col-md-6">
                                                            <input type="submit" class="btn-theme btn btn-block" name="unshadowBan" value="Un Mute" style="background-color: black!important">
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="col-md-6">
                                                            <input type="submit" class="btn-theme btn btn-block" name="shadowBan" value="Mute" style="background-color: black!important">
                                                        </div>
                                                    <?php } ?>
                                                    <?php if($userP['status'] == 2){ ?>
                                                        <div class="col-md-6">
                                                            <input type="submit" class="btn btn-danger btn-block" name="unban" value="Un Suspend">
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="col-md-6">
                                                            <input type="submit" class="btn btn-danger btn-block" name="ban" value="Suspend">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Block Palettes 2020</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../include/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>


<!-- Custom scripts for all pages-->
<script src="<?=$url?>js/sb-admin-2.min.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

</body>
