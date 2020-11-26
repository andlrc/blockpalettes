<?php

session_start();

    require "include/logic.php";

    //Check if user is logged in
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
        header('Location:' . $url);
        exit;
    }

    //Assigns user id to a varible
    $uid = $_SESSION['user_id'];
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['rank'] < 90){
            header('Location: ' . $url);
        exit;
        }

        //pagination
        $limit = 12;
        //pull palettes
        $palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY date DESC");
        $palettePull->execute();
        $palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);
        $total_results = $palettePull->rowCount();
        $total_pages = ceil($total_results/$limit);
            
        if (!isset($_GET['page'])) {
            $page = 1;
        } else{
            $page = $_GET['page'];
        }

        $start = ($page-1)*$limit;

        $stmt = $pdo->prepare("SELECT * FROM palette ORDER BY date DESC LIMIT $start, $limit");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_OBJ);
            
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        $conn = null;

        // var_dump($results);
        $no = $page > 1 ? $start+1 : 1;

        $i = 0;

        //Pull blogs
        $blogPull = $pdo->prepare("SELECT * FROM blog LIMIT 5");
        $blogPull->execute();
        $blog = $blogPull->fetchAll(PDO::FETCH_ASSOC);

        //Pull Users
        $userPull = $pdo->prepare("SELECT * FROM user ORDER BY date DESC LIMIT 8");
        $userPull->execute();
        $userP = $userPull->fetchAll(PDO::FETCH_ASSOC);

        //count number of palettes
        $countPull = $pdo->prepare("SELECT COUNT(id) AS num FROM palette");
        $countPull->execute();
        $totalPalettes = $countPull->fetch(PDO::FETCH_ASSOC);

        //count number of featured palettes
        $featurePull = $pdo->prepare("SELECT COUNT(id) AS num FROM palette WHERE featured = 1");
        $featurePull->execute();
        $fPalettes = $featurePull->fetch(PDO::FETCH_ASSOC);

        //Count number of blog posts
        $blogPosts = $pdo->prepare("SELECT count(*) as posts FROM blog");
        $blogPosts->execute();
        $posts = $blogPosts->fetch(PDO::FETCH_ASSOC);

        //Count number of users
        $userCount = $pdo->prepare("SELECT count(*) as users FROM user");
        $userCount->execute();
        $userC = $userCount->fetch(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="<?=$url?>css/main.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <meta name="description" content="We help Minecraft players find eye pleasing palettes to build with as well as create a place to connect with submitting your own palettes and monthly building contest!">
    <meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <title>Block Palettes - Minecraft Building Inspiration Through Blocks</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81969207-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-81969207-1');
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  </head>
  <body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <img src="<?=$url?>img/logotestwhite.png" width="100%">
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Site Function
            </div>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard/palettes">
                    <i class="fas fa-fw fa-th-large"></i>
                    <span>Palettes</span></a>
            </li>


            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-pencil-alt"></i>
                    <span>Blog</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Blog Components:</h6>
                        <a class="collapse-item" href="dashboard/new-post">New Post</a>
                        <a class="collapse-item" href="dashboard/posts">View Posts</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Community
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
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
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hello, <?=ucwords($user['username'])?></span>
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
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
                        <h1 class="h3 mb-0 medium-title">Dashboard</h1>
                    </div>
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                Total Palettes</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$totalPalettes['num']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-th-large fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                Featured Palettes</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$fPalettes['num']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-star fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                Blog Posts</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$posts['posts']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                Registered Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$userC['users']?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-dark">Recent Palettes</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                <?php foreach($results as $p) : ?>
                                    <div class="col-xl-3 col-lg-6 col-sm-6 col-6" style="margin-bottom:15px">
                                        <?php if($p['featured'] == 1) {?>
                                            <div class="feature-pill">
                                                <div class="award shine" style="font-size:12px">
                                                    <i class="fas fa-award"></i> Staff Pick
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                        <?php } ?>
                                        <div style="position: relative">
                                            <a href="<?=$url?>palette/<?=$p['id']?>" target="_blank">
                                                <img src="<?=$url?>img/block/<?=$p['blockOne']?>.png" class="block" style="border-top-left-radius: 6px;">
                                                <img src="<?=$url?>img/block/<?=$p['blockTwo']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockThree']?>.png" class="block" style="border-top-right-radius: 6px;">
                                                <img src="<?=$url?>img/block/<?=$p['blockFour']?>.png" class="block" style="border-bottom-left-radius: 6px;">
                                                <img src="<?=$url?>img/block/<?=$p['blockFive']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockSix']?>.png" class="block" style="border-bottom-right-radius: 6px;">
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-dark">Recent Blog Posts</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body" style="margin-top:-20px">
                                    <?php foreach($blog as $b): ?>
                                        <?php 
                                            $d = date_create($b['date']);
                                            $date = date_format($d,"Y/m/d"); 
                                            $uid = $b['uid'];
                                            $blogUser = $pdo->prepare("SELECT username as uname FROM user WHERE id = $uid");
                                            $blogUser->execute();
                                            $bUser = $blogUser->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <a href="dashboard/edit?p=<?=$b['id']?>">
                                            <div class="blogSnip">
                                                <span class="role-pill" style="background:#e74c3c">Site Update</span>
                                                <h5 class="small-title" style="font-size:16px"><?=ucwords($b['title'])?></h5>
                                                <p class="subText" style="margin-bottom:0px; font-size:12px"><i class="fas fa-user"></i> <b><?=ucwords($bUser['uname'])?></b> • <?=$date?></p>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">


                     <!-- Area Chart -->
                     <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-dark">Recent Users</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach($userP as $u) : ?>
                                            <?php 
                                                $d = date_create($u['date']);
                                                $uDate = date_format($d,"Y/m/d"); 
                                                
                                                $rankid = $u['rank'];

                                                $rankPull = $pdo->prepare("SELECT * FROM rank WHERE id = '$rankid'");
                                                $rankPull ->execute();
                                                $rank = $rankPull ->fetch(PDO::FETCH_ASSOC);
                                            ?>
                                            <div class="col-xl-3">
                                                <div class="user-pill">
                                                    <div class="role-pill" style="background:<?=$rank['rank_color']?>">
                                                        <?=ucwords($rank['rank_name'])?>
                                                    </div>
                                                    <p style="margin-bottom:8px; line-height: 20px"; class="small-title"><?=ucwords($u['username'])?></p>
                                                    <p style="margin-bottom:0px;" class="subText"><?=$uDate?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
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
                    <a class="btn btn-primary" href="include/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>
