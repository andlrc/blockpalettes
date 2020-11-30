<?php
session_start();
require "include/logic.php";
if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$popularPull = $pdo->prepare("SELECT blocks, count(*) total
                      from 
                      (
                        select blockOne as blocks
                        from palette
                        union all
                        select blockTwo
                        from palette
                        union all
                        select blockThree
                        from palette
                        union all
                        select blockFour
                        from palette
                        union all
                        select blockFive
                        from palette
                        union all
                        select blockSix
                        from palette
                      ) d
                      group by blocks
                      order by total desc LIMIT 9;

");
$popularPull->execute();
$t = $popularPull->fetchAll(PDO::FETCH_ASSOC);



$id = !empty($_GET['p']) ? trim($_GET['p']) : null;
$pid = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

//pull palette
$pPull = $pdo->prepare("SELECT * FROM palette WHERE id = $pid");
$pPull->execute();
$pf = $pPull->fetch(PDO::FETCH_ASSOC);

if($pf == null){
  header('Location: ../new');
  exit;
}

$blockOne = str_replace("_"," ",$pf['blockOne']);
$blockTwo = str_replace("_"," ",$pf['blockTwo']);
$blockThree = str_replace("_"," ",$pf['blockThree']);
$blockFour = str_replace("_"," ",$pf['blockFour']);
$blockFive = str_replace("_"," ",$pf['blockFive']);
$blockSix = str_replace("_"," ",$pf['blockSix']);

//pull palettes
$palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY RAND ()  LIMIT 12");
$palettePull->execute();
$palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);

$i = 0;

$postUser = $pf['uid'];
if ($postUser > 0){
  $userPull = $pdo->prepare("SELECT * FROM user WHERE id = $postUser");
  $userPull->execute();
  $userP = $userPull->fetch(PDO::FETCH_ASSOC);
}

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
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <title>Block Palettes - Trendy Block Palettes</title>
    <meta name="description" content="We help Minecraft players find eye pleasing palettes to build with as well as create a place to connect with submitting your own palettes and monthly building contest!">
  	<meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative, <?=$blockOne?>, <?=$blockTwo?>, <?=$blockThree?>, <?=$blockFour?>, <?=$blockFive?>, <?=$blockSix?>">
    <!-- Global site tag (gtag.js) - Google Analytics -->


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
          <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 paddingFix">
                    <div style="position: relative">
                        <div class="palette-float">
                          <div class="row">
                            <div class="col-xl-8 col-lg-12" style="padding-bottom:10px;">
                              <div class="flex-thirds">
                                <img src="../img/block/<?=$pf['blockOne']?>.png" class="block">
                                <img src="../img/block/<?=$pf['blockTwo']?>.png" class="block">
                                <img src="../img/block/<?=$pf['blockThree']?>.png" class="block">
                              </div>
                              <div class="flex-thirds">
                                <img src="../img/block/<?=$pf['blockFour']?>.png" class="block">
                                <img src="../img/block/<?=$pf['blockFive']?>.png" class="block">
                                <img src="../img/block/<?=$pf['blockSix']?>.png" class="block">
                              </div>
                            </div>
                            <div class="col-xl-4">
                              <?php 
                                $pid = $pf['id'];
                                $savePull = $pdo->prepare("SELECT COUNT(pid) as num FROM saved WHERE pid = $pid");
                                $savePull->execute();
                                $save = $savePull->fetch(PDO::FETCH_ASSOC);
                                if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
                                  $savedCheckPull = $pdo->prepare("SELECT uid FROM saved WHERE pid = $pid AND uid = $uid");
                                  $savedCheckPull->execute();
                                  $saved = $savedCheckPull->fetch(PDO::FETCH_ASSOC);
                                }
                              ?>

                              <span class="savesFloat">
                                <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                                  <?php if ($saved !== false) { ?>
                                      <form method="post" action="palette">
                                        <input type="hidden" name="uid" value="<?=$uid?>">
                                        <input type="hidden" name="pid" value="<?=$pid?>">
                                        <input type="submit" name="unsave" class="btn-unsave" value="Saved" data-toggle="tooltip" data-placement="bottom" title="Click to unsave">
                                      </form>
                                    <?php } else { ?>
                                      <form method="post" action="palette">
                                        <input type="hidden" name="uid" value="<?=$uid?>">
                                        <input type="hidden" name="pid" value="<?=$pid?>">
                                        <input type="submit" name="save" class="btn-save" value="<?=$save['num'];?> Saves" data-toggle="tooltip" data-placement="bottom" title="Click to save">
                                      </form>
                                    <?php } ?>
                                    
                                  <?php } else {?>
                                    <div class="" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">
                                      <span class="btn-save" data-toggle="tooltip" data-placement="bottom" title="Sign in to save palettes!"><?=$save['num'];?> Saves</span>
                                    </div>
                                <?php } ?>
                              </span>

                              <h2 class="medium-title">Palette #<?=$pf['id']?></h2>
                              <?php if ($postUser > 0){ ?>
                                <?php 
                                
                                $userRank = $userP['rank'];
                                $rankPull = $pdo->prepare("SELECT * FROM rank WHERE id = $userRank");
                                $rankPull->execute();
                                $rank = $rankPull->fetch(PDO::FETCH_ASSOC);

                                ?>
                                <div class="postUser" color="">
                                  <a href="../profile/<?=$userP['username']?>" class="userLink">
                                    By: <?=ucwords($userP['username'])?> <span class="userRank" style="background:<?=$rank['rank_color']?>"><?=ucwords(mb_substr($rank['rank_name'], 0, 1, "UTF-8"))?></span>
                                  </a>
                                </div>
                              <?php } ?>
                              <div class="subtext">
                                  <?php if($pf['featured'] == 1) { ?>
                                  <div class="award half">
                                      <i class="fas fa-award"></i> Staff Pick
                                      </div>
                                      <div class="time right half">
                                      <?=time_elapsed_string($pf['date'])?>
                                  </div>
                                  <?php } else { ?>
                                  <div class="time" style="float:left">
                                  <?=time_elapsed_string($pf['date'])?>
                                  </div>
                                  <?php } ?>
                              </div>
                              <div class="blocks">
                                  <h4 class="small-title">Blocks Used:</h4>
                                  <ul>
                                      <li class="listText"><img src="../img/block/<?=$pf['blockOne']?>.png" class="smallBlock"> <?=ucwords($blockOne)?></li>
                                      <li class="listText"><img src="../img/block/<?=$pf['blockTwo']?>.png" class="smallBlock"> <?=ucwords($blockTwo)?></li>
                                      <li class="listText"><img src="../img/block/<?=$pf['blockThree']?>.png" class="smallBlock"> <?=ucwords($blockThree)?></li>
                                      <li class="listText"><img src="../img/block/<?=$pf['blockFour']?>.png" class="smallBlock"> <?=ucwords($blockFour)?></li>
                                      <li class="listText"><img src="../img/block/<?=$pf['blockFive']?>.png" class="smallBlock"> <?=ucwords($blockFive)?></li>
                                      <li class="listText"><img src="../img/block/<?=$pf['blockSix']?>.png" class="smallBlock"> <?=ucwords($blockSix)?></li>
                                  </ul>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                </div>
                <?php foreach($palette as $p): ?>
                <div class="col-xl-4 col-lg-6 col-sm-6 paddingFix">
                    <div style="position: relative">
                        <a href="<?=$p['id']?>">
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
                                  $pid2 = $p['id'];
                                  $savePull2 = $pdo->prepare("SELECT COUNT(pid) as num FROM saved WHERE pid = $pid");
                                  $savePull2->execute();
                                  $save2 = $savePull2->fetch(PDO::FETCH_ASSOC);
                                  if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
                                    $savedCheckPull2 = $pdo->prepare("SELECT uid FROM saved WHERE pid = $pid2 AND uid = $uid");
                                    $savedCheckPull2->execute();
                                    $saved2 = $savedCheckPull2->fetch(PDO::FETCH_ASSOC);
                                  }                                 
                                ?>
                                <div class="subtext">
                                  <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                                    <div class="time left half">
                                      <?php if ($saved2 !== false) { ?>
                                        <span class="btn-unsave">Saved</span>
                                      <?php } else { ?>
                                        <span class="btn-save"><?=$save2['num'];?> Saves</span>
                                      <?php } ?>
                                      </div>
                                    <?php } else {?>
                                      <div class="time left half" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">
                                        <span class="btn-save" data-toggle="tooltip" data-placement="bottom" title="Sign in to save palettes!"><?=$save2['num'];?> Saves</span>
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
                <?php endforeach;?>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 d-lg-block d-md-none d-sm-none">
              <h3 class="medium-title">Filter Palettes</h3>
              <p style="margin-bottom:0px">Search Block</p>
              <form method="get" style="padding-bottom:25px" action="<?=$url?>new">
              <div class="input-group">
                  <select id="select-1" name="filter" class="form-control" placeholder="Search a block..." required> 
                  <option value="" class="cursor">Search a block...</option>
                      <?php 
                        foreach( $images as $image ):
                          $extCut = str_replace(".png","","$image");
                          $cleanStr = str_replace("img/block/","","$extCut");

                          $blockName = str_replace("_"," ",$cleanStr);
                      ?>
                      <option value="<?=$cleanStr?>" class="cursor"><?=ucwords($blockName)?></option>
                      <?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn-filter btn"><i class="fas fa-search"></i></button>
              </div>
              </form>
              <p style="margin-bottom:0px">Popular Blocks</p>
              <?php foreach($t as $popular): ?>
                <?php $block = str_replace("_"," ",$popular['blocks']); ?>
                <a href="<?=$url?>new?filter=<?=$popular['blocks']?>">
                  <div class="block-pill">
                    <img src="<?=$url?>img/block/<?=$popular['blocks']?>.png"> <b><?=ucwords($block)?></b><br>
                  </div>
                </a>
              <?php endforeach; ?>
              <div align="center" style="padding-top:25px">
                <i class="fas fa-bell"></i> <i class="subText">More Filters Coming Soon</i>
              </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php') ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
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
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
  </body>
  
</html>