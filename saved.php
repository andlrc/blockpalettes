<?php 
session_start();
include "include/logic.php";

if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else if(isset($_COOKIE['user_logged'])) { 
  $_SESSION['user_id'] = $_COOKIE['user_logged'];
  $_SESSION['logged_in'] = time();
} else {
    header('Location:' . $url);
}



$saveCheck = $pdo->prepare("SELECT pid FROM saved WHERE uid = $uid");
$saveCheck->execute();
$save = $saveCheck->fetchAll(PDO::FETCH_ASSOC);



$dir = "img/block/*.png";
//get the list of all files with .jpg extension in the directory and safe it in an array named $images
 $images = glob( $dir );

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
    <title>Block Palettes - Saved Palettes</title>

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
    <script data-ad-client="ca-pub-9529646541661119" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

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
    <div class="palettes">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="title" style="padding-bottom:15px">Saved Palettes</div>
              <?php if($save == null){ ?>
              You have not saved any palettes yet :(
              <?php } ?>
            <div style="padding-bottom:15px"></div> 
          </div>
          <?php foreach($save as $s): ?>

            <?php 
                $pid = $s['pid'];
                $palettePull = $pdo->prepare("SELECT * FROM palette WHERE id = $pid");
                $palettePull->execute();
                $palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);

            ?>
            <?php foreach($palette as $p): ?>
          <div class="col-xl-3 col-lg-4 col-md-6 paddingFix">
            <div style="position: relative">
            <a href="<?=$url?>palette/<?=$p['id']?>">
                <div class="palette-float">
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
                        <?php if ($saved !== false) { ?>
                          <span class="btn-unsave">Saved</span>
                        <?php } else { ?>
                          <span class="btn-save"><?=$save['num'];?> Saves</span>
                        <?php } ?>
                        </div>
                      <?php } else {?>
                        <div class="time left half" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">
                          <span class="btn-save" data-toggle="tooltip" data-placement="bottom" title="Sign in to save palettes!"><?=$save['num'];?> Saves</span>
                        </div>
                      <?php } ?>
                      <?php if($p['featured'] == 1){ ?>
                        <div class="award right half shine">
                            <i class="fas fa-award"></i> Staff Pick
                        </div>
                      <?php } else { ?>
                        <div class="time right half">
                            <?=time_elapsed_string($p['date'])?>
                        </div>
                      <?php } ?>
                  </div>
                </div>
                </a>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>


    <?php include('include/footer.php') ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="medium-title" id="exampleModalLongTitle">Updates</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="small-title">Welcome to the NEW and IMPROVED Block Palettes!</h4>
            <p>As you can see a few things have changed from the previous site.</p>
            <ul>
            <li>You can now create palettes in real time! Head over to the <a href="<?=$url?>/submit">create</a> page and create a beautiful block palette.</li>
            <li>We are still curating palettes. On the <a href="<?=$url?>">featured</a> page our staff picks 12 users submitted palettes every week to be apart of the collection!</li>
            <li>We have created an <a href="https://www.instagram.com/blockpalettes/">Instagram</a> where we will post daily palettes from the <a href="<?=$url?>/new">new palettes</a> page.</li>
            </ul>
            <p>This is just the beginning with this new platform. We have many great updates on the way that will continue to improve the site into the future!</p>
            <p>Thank you for the support!<br><i>- Block Palettes Staff</i></p>
          </div>    
        </div>
      </div>
    </div>
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
  </body>
</html>