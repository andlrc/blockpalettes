<?php 
session_start();
include "include/logic.php";



if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} 

  $palettesCount = $pdo->prepare("SELECT COUNT(*) as num FROM palette");
  $palettesCount->execute();
  $pCount = $palettesCount->fetch(PDO::FETCH_ASSOC);

  $userCount = $pdo->prepare("SELECT COUNT(*) as num FROM user");
  $userCount->execute();
  $uCount = $userCount->fetch(PDO::FETCH_ASSOC);

  $palettesTodayCount = $pdo->prepare("SELECT COUNT(*) as num FROM palette where date(date)=date(now());");
  $palettesTodayCount->execute();
  $pTCount = $palettesTodayCount->fetch(PDO::FETCH_ASSOC);

  $userTodayCount = $pdo->prepare("SELECT COUNT(*) as num FROM user where date(date)=date(now());");
  $userTodayCount->execute();
  $uTCount = $userTodayCount->fetch(PDO::FETCH_ASSOC);


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
    <meta name="description" content="Check out new block palettes submitted by the Minecraft community. Get building inspiration or create and share your own block palettes">
    <meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <title>Block Palettes - About</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script>
        $(document).ready(function () {
          $('select').selectize({
              sortField: 'text'
          });
      });
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81969207-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-81969207-1');
    </script>

  </head>
  <body>
    <!-- Nav -->
    <div class="topbar" data-toggle="modal" data-target="#exampleModalCenter">
      <div class="container">
        <div class="topbarText">
          NEW SITE - Click Here To Find Out More
        </div> 
      </div>
    </div>
    <?php include('include/header.php'); ?>
    <div class="about">
      <div class="container-fluid align-middle">
        <div class="row">
          <div class="col-xl-3 col-lg-3 col-md-6 col-6 quickAbout" align="center">
            <h5 class="medium-title" style="color:white;font-size:40px;margin-bottom:0px"><?=$pCount['num']?></h5>
            Palettes
          </div>
          <div class="col-xl-3 col-lg-3 col-md-6 col-6 quickAbout" align="center">
            <h5 class="medium-title" style="color:white;font-size:40px;margin-bottom:0px"><?=$uCount['num']?></h5>
            Users
          </div>
          <div class="col-xl-3 col-lg-3 col-md-6 col-6 quickAbout" align="center">
            <h5 class="medium-title" style="color:white;font-size:40px;margin-bottom:0px"><?=$pTCount['num']?></h5>
            Palettes Today
          </div>
          <div class="col-xl-3 col-lg-3 col-md-6 col-6 quickAbout" align="center">
            <h5 class="medium-title" style="color:white;font-size:40px;margin-bottom:0px"><?=$uTCount['num']?></h5>
            Registered Today
          </div>
          
        </div>
      </div>
    </div>
    <div class="aboutTextArea">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-10 offset-1">
            <div class="row">
              <div class="col-xl-3 col-lg-3 col-md-3" style="padding-bottom:35px">
                <img src="img/biglogo.png" width="100%">
              </div>
              <div class="col-xl-9 col-lg-9 col-md-12">
                <h2 class="title">Block Palettes</h2>
                <p>We help Minecraft players find eye pleasing palettes to build with as well as create a place to connect with monthly building contest and showcases of the amazing things people build!</p>
                <p>We are continually expanding our website to create the best experience possible and help people find/share Minecraft building inspiration.</p>
                <a href="https://twitter.com/ntbol"><p class="small-title" style="font-size:18px">Built by Ntbol</p></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include "include/footer.php" ?>
    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  </body>
</html>