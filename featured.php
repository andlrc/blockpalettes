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
} else if(isset($_SESSION['userRegister'])) {
  $error = "success";
  unset($_SESSION['userRegister']);
}
else {
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
    <div class="topbar" data-toggle="modal" data-target="#exampleModalCenter">
      <div class="container">
        <div class="topbarText">
          NEW SITE - Click Here To Find Out More
        </div> 
      </div>
    </div>
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
    <?php }else if($error == "success") { ?>
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
                    <div class="award right half shine">
                      <i class="fas fa-award"></i> Staff Pick
                    </div>
                  </div>
                </div>
                </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>


    <?php include('include/footer.php') ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Modal -->
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
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
  </body>
</html>