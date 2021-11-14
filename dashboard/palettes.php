<?php
error_reporting(1);
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

        if ($user['rank'] < 90){
            header('Location: ' . $url);
        exit;
        }

        $path = $_SERVER['REQUEST_URI'];

        //pagination

        $limit = 28;

        if(empty($_GET)){
            $uri = $path . '?';
          } else {
            $uri = $path . '&';
          }
          

          $palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY id DESC");
          $palettePull->execute();


          $total_results = $palettePull->rowCount();
          $total_pages = ceil($total_results/$limit);
              
          if (!isset($_GET['p'])) {
              $page = 1;
          } else{
              $page = htmlspecialchars(addslashes($_GET['p']));
          }

          $start = ($page-1)*$limit;

          $stmt = $pdo->prepare("SELECT * FROM palette WHERE hidden = 0 ORDER BY id DESC LIMIT $start, $limit");
          $stmt->execute();
       
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_OBJ);
                
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
        $conn = null;

        // var_dump($results);
        $no = $page > 1 ? $start+1 : 1;

        $i = 0;

        $total_pages = $total_pages - 1;


        $hiddenPalettes = $pdo->prepare("SELECT * FROM palette WHERE hidden = 1");
        $hiddenPalettes->execute();
        $hidden = $hiddenPalettes->fetchAll(PDO::FETCH_ASSOC);

        $featuredPalettes = $pdo->prepare("SELECT * FROM palette WHERE featured = 1");
        $featuredPalettes->execute();
        $featured = $featuredPalettes->fetchAll(PDO::FETCH_ASSOC);

        $userProfilePull = $pdo->prepare("SELECT * FROM user_profile WHERE uid = $uid");
        $userProfilePull->execute();
        $userProfile = $userProfilePull->fetch(PDO::FETCH_ASSOC);

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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard">
                <img src="<?=$url?>img/logotestwhite.png" width="100%">
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="../dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Site Function
            </div>


            <li class="nav-item active">
                <a class="nav-link" href="../dashboard/palettes">
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
            <li class="nav-item">
                <a class="nav-link" href="users">
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
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top"style="border-bottom: #ededed solid 1px">

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
                                <?php if($userProfile['minecraft_ign'] == null) { ?>
                                    <img src="<?=$url?>img/default.jpg" class="profile-pic" style="margin-left: 10px">
                                <?php } else { ?>
                                    <img src="<?=$url?>include/face.php?u=<?=$userProfile['minecraft_ign']?>&s=48&v=front" class="profile-pic" style="margin-left: 10px">
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
                        <h1 class="h3 mb-0 medium-title">Palettes</h1>
                    </div>
    

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8">
                            <div class="card mb-4">
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
                                                <form action="palettes" method="post">
                                                    <input type="hidden" name="id" value="<?=$p['id']?>">
                                                    <button type="submit" name="hide" class="hide">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                </form>
                                                <form action="palettes" method="post">
                                                    <input type="hidden" name="id" value="<?=$p['id']?>">
                                                    <button type="submit" name="favorite" class="favorite">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                                <form action="palettes" method="post">
                                                    <input type="hidden" name="id" value="<?=$p['id']?>">
                                                    <button type="submit" name="delete" class="delete">
                                                        <i class="fas fa-trash"></i>
                                                     </button>
                                                </form>
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
                                    <?php if(isset($_GET['filter'])){ ?>
                                    <?php } else { ?>
                                        <?php
                                        $pgStart = 1;
                                        $pg = $_GET['p'] - 2;
                                        $pgStart = $pg + 5 > $total_pages ? $total_pages - 4 : $pg; //EDIT fix when reach pages end
                                        $pgStart = $pg < 1 ? 1 : $pg; // This must be after ending correction (previous line)
                                        ?>
                                        
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-center">
                                                <?php if ($pgStart > 1) { // show 1 ?>
                                                <?php
                                                    if(empty($_GET)){
                                                        $uri = $path . '?';
                                                    } else {
                                                        $current_page = $_GET['p'];
                                                        $uri = $path . '&';
                                                    }
                            
                                                    if(strpos($uri, '?p=') !== false){
                                                        $uri = str_replace('p=' . $current_page . '&', "", $uri);
                                                    } elseif(strpos($uri, "&p=") !== false){
                                                        $uri = str_replace('&p=' . $current_page, "", $uri);
                                                    }  
                                                ?>
                                                <li class="page-item"><a href="<?=$uri.'p=1'?>" class="page-link">1</a></li>
                                                <li class="page-item"><a href="<?=$uri.'p=1'?>" class="page-link">...</a></li>
                                                <?php } ?>
                                                <?php for($e = $pgStart; $e <= $total_pages && $e < $pgStart + 5; $e++){?>
                                                <?php 
                                                    if(empty($_GET)){
                                                    $uri = $path . '?';
                                                    } else {
                                                    $current_page = $_GET['p'];
                                                    $uri = $path . '&';
                                                    }

                                                    if(strpos($uri, '?p=') !== false){
                                                    $uri = str_replace('p=' . $current_page . '&', "", $uri);
                                                    } elseif(strpos($uri, "&p=") !== false){
                                                    $uri = str_replace('&p=' . $current_page, "", $uri);
                                                    }      
                                                ?>
                                                <li class="<?= $page == $e ? 'active' : ''; ?> page-item"><a href="<?=$uri.'p=' . $e?>" class="page-link"><?= $e; ?></a></li>
                                                <?php }?>
                                                <?php if ($e < $total_pages) { ?>
                                                <?php 
                                                    if(empty($_GET)){
                                                    $uri = $path . '?';
                                                    } else {
                                                    $current_page = $_GET['p'];
                                                    $uri = $path . '&';
                                                    }

                                                    if(strpos($uri, '?p=') !== false){
                                                    $uri = str_replace('p=' . $current_page . '&', "", $uri);
                                                    } elseif(strpos($uri, "&p=") !== false){
                                                    $uri = str_replace('&p=' . $current_page, "", $uri);
                                                    }      
                                                ?>
                                                    <li class="page-item"><a href="<?=$uri.'p=' . $total_pages?>" class="page-link">...</a></li>
                                                <li class="page-item"><a href="<?=$uri.'p=' . $total_pages?>" class="page-link"><?=$total_pages?></a></li>
                                                <?php } ?>
                                            </ul> 
                                        </nav>
                                    </div>

                                    <?php } ?>
                                </div>
                            </div>
        

                        <!-- Pie Chart -->
                        <div class="col-xl-4">
                            <div class="card mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-dark">Featured Palettes</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body" >
                                    <div class="row">
                                    <?php foreach($featured  as $p): ?>
                                        <div class="col-lg-4" style="margin-bottom:15px">
                                            <form action="palettes" method="post">
                                                <input type="hidden" name="id" value="<?=$p['id']?>">
                                                <button type="submit" name="unfavorite" class="unfavorite">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </form>
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

                            <div class="card mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-dark">Hidden Palettes</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body" >
                                    <div class="row">
                                        <?php foreach($hidden  as $p): ?>
                                            <div class="col-lg-4" style="margin-bottom:15px">
                                                <form action="palettes" method="post">
                                                    <input type="hidden" name="id" value="<?=$p['id']?>">
                                                    <button type="submit" name="unhide" class="unfavorite">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                </form>
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
    <script src="<?=$url?>vendor/jquery/jquery.min.js"></script>
    <script src="<?=$url?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?=$url?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?=$url?>js/sb-admin-2.min.js"></script>

</body>
