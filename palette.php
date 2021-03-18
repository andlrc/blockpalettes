<?php
session_start();
require "include/logic.php";


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
                      order by total desc LIMIT 12;

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
  header('Location: ../palettes');
  exit;
}

$blockOne = str_replace("_"," ",$pf['blockOne']);
$blockTwo = str_replace("_"," ",$pf['blockTwo']);
$blockThree = str_replace("_"," ",$pf['blockThree']);
$blockFour = str_replace("_"," ",$pf['blockFour']);
$blockFive = str_replace("_"," ",$pf['blockFive']);
$blockSix = str_replace("_"," ",$pf['blockSix']);

//pull palettes
$palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY RAND ()  LIMIT 15");
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

$profileDataPull = $pdo->prepare("SELECT * FROM user_profile WHERE uid = $postUser");
$profileDataPull->execute();
$profileData = $profileDataPull->fetch(PDO::FETCH_ASSOC);





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


    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
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
   <style>




   </style>
   
    </head>
  <body>
    <!-- Nav -->
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
                                  <div style=" margin-top: 0px;">
                                    <?php 
                                      // determine if user has already liked this post
                                      $liked = $pdo->prepare("SELECT count(*) as num FROM saved WHERE uid=".$user['id']." AND pid=".$pf['id']."");
                                      $liked->execute();
                                      $like = $liked->fetch(PDO::FETCH_ASSOC);

                                      if ($like['num'] > 0): ?>
                                        <!-- user already likes post -->
                                        <span class="unlike button-unlike" data-id="<?php echo $pf['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Unsave"><i class="fas fa-heart"></i> <span class="likes_count"><?php echo $pf['likes']; ?></span></span>
                                        <span class="like hide button-like" data-id="<?php echo $pf['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Save"><i class="far fa-heart"></i> <span class="likes_count"><?php echo $pf['likes']; ?></span></span> 
                                      <?php else: ?>
                                        <!-- user has not yet liked post -->
                                        <span class="like button-like " data-id="<?php echo $pf['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Save"><i class="far fa-heart"></i> <span class="likes_count"><?php echo $pf['likes']; ?></span></span> 
                                        <span class="unlike hide button-unlike" data-id="<?php echo $pf['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Unsave"><i class="fas fa-heart"></i> <span class="likes_count"><?php echo $pf['likes']; ?></span></span> 
                                      <?php endif ?>

                                    
                                    </div>
                                    
                                  <?php } else {?>
                                    <div class="" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">
                                      <span class="btn-save button-like" data-toggle="tooltip" data-placement="bottom" title="Sign in to save palettes!"><span class="likes_count"><i class="far fa-heart"></i> <span class="likes_count"><?php echo $pf['likes']; ?></span></span>
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
                                      <?php if($profileData['minecraft_ign'] == null) { ?>
                                          <img src="../img/default.jpg" class="profile-pic-small">
                                      <?php } else { ?>
                                          <img src="../include/face.php?u=<?=$profileData['minecraft_ign']?>&s=48&v=front" class="profile-pic-small" onerror="this.src='../img/default.jpg'">
                                      <?php } ?>
                                      <span style="margin-left: 3px"></span>
                                    <?=ucwords($userP['username'])?> <span class="userRank" style="background:<?=$rank['rank_color']?>"><?=ucwords($rank['rank_name'])?></span>
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
                        
                            <div class="palette-float">
                            <a href="<?=$p['id']?>">
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
                                  $pid2 = $p['id'];
                                  $savePull2 = $pdo->prepare("SELECT COUNT(pid) as num FROM saved WHERE pid = $pid2");
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
                <?php endforeach;?>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 d-lg-block d-md-none d-sm-none">
              <h3 class="medium-title">Filter Palettes</h3>
              <p style="margin-bottom:0px">Search Block</p>
              <form method="get" style="padding-bottom:25px" action="<?=$url?>palettes">
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
                <a href="<?=$url?>palettes?filter=<?=$popular['blocks']?>">
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
          url: 'palette.php',
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
          url: 'palette.php',
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