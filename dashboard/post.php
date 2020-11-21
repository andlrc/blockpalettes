<?php

    session_start();

    require "../include/connect.php";
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

        $blogPull = $pdo->prepare("SELECT * FROM blog");
        $blogPull->execute();
        $blog = $blogPull->fetchAll(PDO::FETCH_ASSOC);
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
                <i class="fas fa-bars fa-2x"></i>
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
                    <a href="dashboard">Dashboard</a> <a href="#">Blog Posts</a>
                    <div class="title" style="padding-bottom:15px">Dashboard</div>

                    <h5 class="medium-title">Create a Post</h5>
                    <form action="post" method="post">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                Article Title
                                <input type="text" name="title" placeholder="Title" class="form-control">
                            </div>
                            <div class="col-md-4 form-group">
                                Feature Image
                                <input type="text" name="image" placeholder="Image" class="form-control">
                            </div>
                            <div class="col-md-4 form-group"> 
                                Post type
                                <select class="form-control" id="sel1" name="type" required>
                                    <option value="0">Site Update</option>
                                    <option value="1">Community</option>
                                    <option value="2">News</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                Article
                                <textarea class="form-control form-theme" name="article" rows="20" placeholder="Article goes here" required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                Meta Description
                                <textarea class="form-control form-theme" name="meta" rows="7" placeholder="Meta description goes here" required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input type="submit" name="blog" class="btn btn-theme btn-block" value="Post">
                            </div>
                        </div>
                    </form>

                    <h5 class="medium-title" style="padding-top:50px">Blog Posts</h5>
                    <div class="row">
                        <?php if ($blog == null) { ?>
                            <div class="col-md-12">
                                <h4 class="medium-title">Oh No!</h4>
                                There are currently no blog articles :C
                            </div>
                        <?php } else { ?>
                            <?php foreach($blog as $b): ?>
                                <div class="col-md-4">
                                    <div class="image">
                                        <span class="update-pill">Site Update</span>
                                        <img class="fImage" src="<?=$b['image']?>">
                                    </div>
                                    <div class="article">
                                        <h3 class="small-title"><?=ucwords($b['title'])?></h3>
                                        <p><?=custom_echo($b['article'], 150);?></p>
                                        <?php $urlPost = str_replace(' ', '_', $b['title']) ?>
                                        <a href="edit?p=<?=$b['id']?>" class="btn btn-theme btn-block">Edit</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div