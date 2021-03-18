<?php 
session_start();

require "include/logic.php";
//Check if user is logged in
if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_SESSION['emailError'])) {
  $error = "email";
  unset($_SESSION['emailError']);
} else if(isset($_SESSION['userError'])) {
  $error = "user";
  unset($_SESSION['userError']);
} else {
  $error = "";
}

if(isset($_SESSION['userRegister'])) {
  $success = "successReg";
  unset($_SESSION['userRegister']);
} else {
  $success = "";
}

if(isset($_SESSION['invalidUsername'])) {
    $inUser = "invalidUser";
    unset($_SESSION['invalidUsername']);
} else {
    $inUser = "";
}

if(isset($_SESSION['badEmail'])) {
    $error = "bad email";
    unset($_SESSION['badEmail']);
} else if(isset($_SESSION['badPassword'])) {
    $error = "bad password";
    unset($_SESSION['badPassword']);
} else {
    $error = "";
}



//pagination
$limit = 12;
//pull palettes
$palettePull = $pdo->prepare("SELECT * FROM palette WHERE featured = 1");
$palettePull->execute();
$palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);


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
    <meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative, Medieval, fantasy, Farm, Jungle, Modern, Gothic, Scary">
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
    <?php include('include/header.php'); ?>
    <!-- End Nav -->
    <?php if($error == "email") { ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="bs-example"> 
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Warning!</strong> That email is already in use!
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }else if($error == "user") { ?>
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="bs-example"> 
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Warning!</strong> That username is already in use!
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }else if($success == "successReg") { ?>
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="bs-example"> 
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> You are now registered. <a data-toggle="modal" data-target="#loginModal" style="cursor: pointer"><b>Click here to login</b></a>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }else if($inUser == "invalidUser") { ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="bs-example">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Error!</strong> Username must only contain alphanumeric characters
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }else if($error == "bad password") { ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="bs-example">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Warning!</strong> There was an issue with your entered password
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }else if($error == "bad email") { ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="bs-example">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Warning!</strong> Cannot find any users with that email
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="palettes">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="title" style="padding-bottom:5px">Featured Palettes</div>
            <p style="padding-bottom:25px">Featured block palettes are hand picked by our staff weekly.</p>
          </div>
          <?php foreach($palette as $p): ?>
          <div class="col-xl-3 col-lg-4 col-md-6 paddingFix">
            <div style="position: relative">
                <div class="palette-float">
                <a href="<?=$url?>palette/<?=$p['id']?>">
                  <div class="flex-thirds">
                    <img src="<?=$url?>img/block/<?=$p['blockOne']?>.png" class="block">
                    <img src="<?=$url?>img/block/<?=$p['blockTwo']?>.png" class="block">
                    <img src="<?=$url?>img/block/<?=$p['blockThree']?>.png" class="block">
                  </div>
                  <div class="flex-thirds">
                    <img src="<?=$url?>img/block/<?=$p['blockFour']?>.png" class="block">
                    <img src="<?=$url?>img/block/<?=$p['blockFive']?>.png" class="block">
                    <img src="<?=$url?>img/block/<?=$p['blockSix']?>.png" class="block">
                  </div>
                  </a>
                  
                  <?php 
                    $pid = $p['id'];
                    $savePull = $pdo->prepare("SELECT COUNT(pid) as num FROM saved WHERE pid = $pid");
                    $savePull->execute();
                    $save = $savePull->fetch(PDO::FETCH_ASSOC);
                    if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
                      $savedCheckPull = $pdo->prepare("SELECT uid FROM saved WHERE pid = $pid AND uid = $uid");
                      $savedCheckPull->execute();
                      $saved = $savedCheckPull->fetch(PDO::FETCH_ASSOC);
                    }

                    
                  ?>
                  <div class="subtext">
                    <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                      <div class="time left half">
                            <?php
                              $liked = $pdo->prepare("SELECT count(*) as num FROM saved WHERE uid=".$user['id']." AND pid=".$p['id']."");
                              $liked->execute();
                              $like = $liked->fetch(PDO::FETCH_ASSOC);

                              if ($like['num'] > 0): ?>
                                <!-- user already likes post -->
                                <span class="unlike unlikesmall" data-id="<?php echo $p['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Unsave"><i class="fas fa-heart"></i> <span class="likes_count"><?php echo $p['likes']; ?></span></span>
                                <span class="like hide" data-id="<?php echo $p['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Save"><i class="far fa-heart"></i> <span class="likes_count"><?php echo $p['likes']; ?></span></span> 
                              <?php else: ?>
                                <!-- user has not yet liked post -->
                                <span class="like" data-id="<?php echo $p['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Save"><i class="far fa-heart"></i> <span class="likes_count"><?php echo $p['likes']; ?></span></span> 
                                <span class="unlike unlikesmall hide" data-id="<?php echo $p['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Unsave"><i class="fas fa-heart"></i> <span class="likes_count"><?php echo $p['likes']; ?></span></span> 
                              <?php endif ?>
                        </div>
                      <?php } else {?>
                        <div class="time left half" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">
                          <span class="btn-save" data-toggle="tooltip" data-placement="bottom" title="Sign in to save palettes!"><i class="far fa-heart"></i> <span class="likes_count"><?php echo $p['likes']; ?></span></span>
                        </div>
                      <?php } ?>
                    <div class="award right half shine">
                      <i class="fas fa-award"></i> Staff Pick
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>


    <?php include('include/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>


    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
    <script>
    $(document).ready(function(){
      // when the user clicks on like
      $('.like').on('click', function(){
        var postid = $(this).data('id');
            $post = $(this);

        $.ajax({
          url: 'featured.php',
          type: 'post',
          data: {
            'liked': 1,
            'postid': postid
          },
          success: function(response){
            $post.parent().find('span.likes_count').text(response + "");
            $post.addClass('hide');
            $post.siblings().removeClass('hide');
          }
        });
      });

      // when the user clicks on unlike
      $('.unlike').on('click', function(){
        var postid = $(this).data('id');
          $post = $(this);

        $.ajax({
          url: 'featured.php',
          type: 'post',
          data: {
            'unliked': 1,
            'postid': postid
          },
          success: function(response){
            $post.parent().find('span.likes_count').text(response + "");
            $post.addClass('hide');
            $post.siblings().removeClass('hide');
          }
        });
      });
    });
  </script>
  </body>
</html>