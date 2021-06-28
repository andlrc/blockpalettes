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

$palettePull = $pdo->prepare("SELECT * FROM palette WHERE featured = 1");
$palettePull->execute();
$palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['filtered'])){
  $pidIn = !empty($_POST['type']) ? trim($_POST['type']) : null;
  $pid = htmlspecialchars($pidIn, ENT_QUOTES, 'UTF-8');

  if ($pid == "old"){
    $palettePull = $pdo->prepare("SELECT * FROM palette WHERE featured = 0");
    $palettePull->execute();
    $palette = $palettePull->fetchAll(PDO::FETCH_ASSOC);
  }
  exit();
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
    <meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative, Medieval, fantasy, Farm, Jungle, Modern, Gothic, Scary">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <title>Block Palettes - Mideval Fortress Contest</title>
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
  
    <div class="palettes">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <span id="clockdiv" class="contest-time-float" data-toggle="tooltip" data-placement="top" title="Time left in contest">
              <i class="far fa-clock"></i> 
              <span class="days"></span>:
              <span class="hours"></span>:
              <span class="minutes"></span>:
              <span class="seconds"></span>
            </span>
            <span class="contest-user-float" data-toggle="tooltip" data-placement="top" title="# of Entries">
            <i class="far fa-user"></i>  342
            </span>
            <img src="https://i.imgur.com/kcSdxHq.jpeg" class="contest-image">
          </div>
          <div class="col-md-12">
            <div class="contest-float">
              <div class="row">
                <div class="col-lg-6">
                  <span><i class="fas fa-circle contest-live"></i> Active Contest</span>
                  <h1 class="medium-title">Contest Name</h1>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pulvinar sapien eget turpis tempus faucibus. Praesent maximus mollis tellus, eu posuere lacus suscipit vitae. Duis pulvinar condimentum felis, congue molestie lacus dapibus ut.</p>
                </div>
                <div class="col-lg-6" >
                  <div class="prizes">
                    <p class="small-title" style="margin:0px">Prizes</p>
                    <i class="fas fa-circle fa-4x"></i> 
                    <i class="fas fa-circle fa-4x"></i> 
                    <i class="fas fa-circle fa-4x"></i> 
                    <i class="fas fa-circle fa-4x"></i> 
                  </div>
                </div>
              </div>
            </div>
            <div class="contest-float-submit">
              <div class="row">
                <div class="col-md-6 offset-md-3">
                  <a href="" class="btn btn-theme btn-block">Submit Palette</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12" style="margin-top:35px">
            <div class="row">
              <div class="col-6">
                <h2 class="medium-title">Recent Entries</h2>
              </div>
              <div class="col-6">
                
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
    <script type="text/javascript">
        function getTimeRemaining(endtime) {
        const total = Date.parse(endtime) - Date.parse(new Date());
        const seconds = Math.floor((total / 1000) % 60);
        const minutes = Math.floor((total / 1000 / 60) % 60);
        const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
        const days = Math.floor(total / (1000 * 60 * 60 * 24));
        
        return {
            total,
            days,
            hours,
            minutes,
            seconds
        };
        }

        function initializeClock(id, endtime) {
        const clock = document.getElementById(id);
        const daysSpan = clock.querySelector('.days');
        const hoursSpan = clock.querySelector('.hours');
        const minutesSpan = clock.querySelector('.minutes');
        const secondsSpan = clock.querySelector('.seconds');

        function updateClock() {
            const t = getTimeRemaining(endtime);

            daysSpan.innerHTML = ('0' + t.days).slice(-2);
            hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
            secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

            if (t.total <= 0) {
            clearInterval(timeinterval);
            }
        }

        updateClock();
        const timeinterval = setInterval(updateClock, 1000);
        }

        const deadline = "Sun Apr 24 2021 13:55:56 GMT-0400 (EDT)";
        initializeClock('clockdiv', deadline);
          
    </script>
    <script>

    $(document).ready(function(){
      // when the user clicks on like
      $('#filter').change(function(){
        var type = $(this).val();
        console.log(type);

        $.ajax({
          url: 'contest.php',
          type: 'post',
          data: {
            'filtered': 1,
            'type': type
          }
      });
    });
  });

    $(document).ready(function(){
      // when the user clicks on like
      $('.like').on('click', function(){
        var postid = $(this).data('id');
            $post = $(this);

        $.ajax({
          url: 'palettes.php',
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
          url: 'palettes.php',
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