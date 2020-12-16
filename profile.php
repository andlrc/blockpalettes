<?php 
error_reporting(0);
session_start();

require "include/logic.php";
//Check if user is logged in
$uid = "";

if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}


$getid = !empty($_GET['p']) ? trim($_GET['p']) : null;
$username = htmlspecialchars($getid, ENT_QUOTES, 'UTF-8');

$profileGet = $pdo->prepare("SELECT * FROM user WHERE username = '$username'");
$profileGet->execute();
$userProfile = $profileGet->fetch(PDO::FETCH_ASSOC);

if ($userProfile == null){
    header('Location: ' . $url . '');
}

$rankid = $userProfile['rank'];

$id = $userProfile['id'];

$rankPull = $pdo->prepare("SELECT * FROM rank WHERE id = '$rankid'");
$rankPull ->execute();
$rank = $rankPull ->fetch(PDO::FETCH_ASSOC);


//pull palettes
$palettePull = $pdo->prepare("SELECT * FROM palette WHERE uid = $id");
$palettePull->execute();
$palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);

$profileDataPull = $pdo->prepare("SELECT * FROM user_profile WHERE uid = $id");
$profileDataPull->execute();
$profileData = $profileDataPull->fetch(PDO::FETCH_ASSOC);

$userAwardsPull = $pdo->prepare("SELECT * FROM user_awards WHERE uid = $id");
$userAwardsPull->execute();
$userAwards = $userAwardsPull->fetchAll(PDO::FETCH_ASSOC);


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
    <script>
        function countChar(val) {
            var len = val.value.length;
            if (len >= 150) {
                val.value = val.value.substring(0,150);
            } else {
                $('#charNum').text(150 - len);
            }
        };
    </script>

  </head>
  <body>
    <!-- Nav -->
    <?php include('include/header.php'); ?>
    <!-- End Nav -->
    <div class="palettes">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="profile-float" >
                <div class="row align-middle" >
                    <div class="col-sm-8" >
                        <?php if($profileData['minecraft_ign'] == null) { ?>
                            <img src="../img/default.jpg" class="profile-pic-large">
                        <?php } else { ?>
                            <img src="../include/face.php?u=<?=$profileData['minecraft_ign']?>&s=48&v=front" class="profile-pic-large" onerror="this.src='../img/default.jpg'">
                        <?php } ?>
                        <div class="user-info">
                            <h2 class="medium-title" style="margin-bottom:0px"><?=ucwords($userProfile['username'])?> 
                              <?php if($userProfile['id'] == $uid ){ ?><a data-toggle="modal" data-target="#profileModal" style="cursor: pointer" class="btn btn-theme-small"><i class="fas fa-pencil-alt"></i> Edit Profile</a><?php } ?>
                            </h2>
                            <div class="role-pill" style="background:<?=$rank['rank_color']?>"><?=ucwords($rank['rank_name'])?></div>
                        </div>
                    </div>
                    <div class="col-sm-4 award-area">
                        <h3 class="small-title" style="font-size:18px; margin-bottom:0px">Awards</h3>
                        <div class="award-box">
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
                                    <img class="award-icon" src="<?=$url?>img/awards/<?=$awards['award_icon']?>" data-toggle="tooltip" data-placement="right" title="<?=$awards['award_bio']?>">
                                <?php endforeach; ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="profile-notes">
                            <?php if($profileData['bio'] == null){ ?>
                              <i>No information given.</i>
                            <?php } else { ?>
                              <i><?=$profileData['bio']?></i>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="col-md-12">
              <?php if($userProfile['id'] == $uid){ ?>
                <div class="small-title" style="padding-bottom:5px">Your Palettes</div>
              <?php } else { ?>
                <div class="small-title" style="padding-bottom:5px"><?=ucwords($userProfile['username']);?>'s Palettes</div>
              <?php } ?>

              <?php if ($palette == null) { ?>
                User has not created any palettes yet.
              <?php }?>
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

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div align="right" style="padding: 10px">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 35px">
                    <div align="center">
                        <h3 class="medium-title" id="profileModalTitle">Edit Profile</h3>
                        <p class="text">More setting coming soon!</p>
                    </div>
                    <form action="<?=$url?>include/logic.php" method="post">
                        <div class="form-group">
                          Bio
                          <textarea maxlength="150" rows="5" class="form-control" name="bio" style="resize: none;" placeholder="Enter some text" onkeyup="countChar(this)"><?=$profileData['bio']?></textarea>
                          <div id="charNum" align="right" class="tiny-text" style="margin-bottom: 5px; margin-top: -40px; margin-right: 5px">150</div>
                        </div>
                        <input type="hidden" name="uid" value="<?=$userProfile['id']?>">
                        <input type="hidden" name="username" value="<?=$userProfile['username']?>">
                        <button class="btn btn-theme btn-block" type="submit" name="updateprofile"><b>Update</b></button>
                    </form>
                </div>
            </div>
        </div>
    </div>


  </body>
</html>