<?php

session_start();

    require "include/connect.php";
    require "include/logic.php";

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
    }

    //featured pull
    $featurePull = $pdo->prepare("SELECT * FROM palette WHERE featured = 1");
    $featurePull->execute();
    $feature = $featurePull->fetchAll(PDO::FETCH_ASSOC);

    //pagination
    $limit = 15;
    //pull palettes
    $palettePull = $pdo->prepare("SELECT * FROM palette WHERE featured = 0 ORDER BY date DESC");
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

    $stmt = $pdo->prepare("SELECT * FROM palette WHERE featured = 0 ORDER BY date DESC LIMIT $start, $limit");
    $stmt->execute();

    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_OBJ);
        
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    $conn = null;

    // var_dump($results);
    $no = $page > 1 ? $start+1 : 1;

    $i = 0;
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
  <body>
    <!-- Nav -->
    <div class="custom-header" id="#">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?=$url?>">
                    <img src="<?=$url?>img/logotest.png" class="logo-size">
                </a>
                <button class="navbar-toggler custom-toggler" id="hamburger" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="<?=$url?>images/hamburger-solid.svg" width="35px">
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav ml-auto custom-nav-text centeredContent">
                        <li class="nav-item">
                            <a href="include/logout.php" class="nav-link btn btn-theme-nav">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!-- End Nav -->
    <div class="palettes">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p style="padding-bottom:0px">Welcome, <?=$user['email']?></p>
                    <div class="title" style="padding-bottom:15px">Dashboard</div>

                    <h5 class="medium-title">Featured Palettes</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col"></th>
                                <th scope="col">link</th>
                                <th scope="col">Favorite</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($feature as $p) : ?>
                            <tr>
                                <th scope="row" class="align-middle"><?=$p['id']?></th>
                                <td class="align-middle">
                                    <div class="col-lg-4 col-4">
                                        <div style="position: relative">
                                            <a href="<?=$url?>palette/<?=$p['id']?>" target="_blank">
                                                <img src="<?=$url?>img/block/<?=$p['blockOne']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockTwo']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockThree']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockFour']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockFive']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockSix']?>.png" class="block">
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle"><a href="<?=$url?>palette/<?=$p['id']?>" target="_blank">Link to palette</a></td>
                                <td class="align-middle">
                                    <form action="dashboard" method="post">
                                        <input type="hidden" name="id" value="<?=$p['id']?>">
                                        <button type="submit" name="unfavorite" class="btn favbtn"><i class="fas fa-share"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h5 class="medium-title" style="padding-top:50px">All Palettes</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col"></th>
                                <th scope="col">link</th>
                                <th scope="col">Favorite</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($results as $p) : ?>
                            <tr>
                                <th scope="row" class="align-middle"><?=$p['id']?></th>
                                <td class="align-middle">
                                    <div class="col-lg-4 col-4">
                                        <div style="position: relative">
                                            <a href="<?=$url?>palette/<?=$p['id']?>" target="_blank">
                                                <img src="<?=$url?>img/block/<?=$p['blockOne']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockTwo']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockThree']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockFour']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockFive']?>.png" class="block">
                                                <img src="<?=$url?>img/block/<?=$p['blockSix']?>.png" class="block">
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle"><a href="<?=$url?>palette/<?=$p['id']?>" target="_blank">Link to palette</a></td>
                                <td class="align-middle">
                                    <form action="dashboard" method="post">
                                        <input type="hidden" name="id" value="<?=$p['id']?>">
                                        <button type="submit" name="favorite" class="btn favbtn"><i class="fas fa-star"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a href="<?=$url?>dashboard" class="page-link"><i class="fas fa-chevron-double-left"></i></a></li>
                            <?php for($p=1; $p<=$total_pages; $p++){?> 
                            <li class="<?= $page == $p ? 'active' : ''; ?> page-item"><a href="<?=$url?><?= 'dashboard/'.$p; ?>" class="page-link"><?= $p; ?></a></li>
                            <?php }?>
                            <li class="page-item"><a href="<?=$url?>dashboard/<?= $total_pages; ?>" class="page-link"><i class="fas fa-chevron-double-right"></i></a></li>
                        </ul> 
                    </nav>
                </div>
            </div>
        </div>
    </div