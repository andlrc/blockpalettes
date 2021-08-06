<?php
error_reporting(0);
session_start();
include "include/logic.php";



if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} 


$palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY id DESC LIMIT 21");
$palettePull->execute();
$results = $palettePull->fetchAll(PDO::FETCH_ASSOC);


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



if(isset($_GET['filter'])){
  $dataInput = !empty($_GET['filter']) ? trim($_GET['filter']) : null;
  $block = htmlspecialchars($dataInput, ENT_QUOTES, 'UTF-8');

  $palettePull = $pdo->prepare("SELECT * FROM palette WHERE blockOne LIKE '$block' 
                               OR blockTwo LIKE '$block' 
                               OR blockThree LIKE '$block' 
                               OR blockFour LIKE '$block' 
                               OR blockFive LIKE '$block' 
                               OR blockSix LIKE '$block' LIMIT 21");
  $palettePull->execute();
  $results = $palettePull->fetchAll(PDO::FETCH_ASSOC);



} 
    
if (isset($_GET['removeFilter'])) {
  header('Location: palettes');
}

//pull palettes

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
    <title>Block Palettes - Block Palettes For Minecraft Builders</title>

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
      <script src="js/filters.js"></script>
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
    <?php include('include/header.php'); ?>
    <div class="palettes">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="title" style="padding-bottom:15px">Palettes</div>
          </div>

          <div class="col-md-12 d-xl-none d-lg-none d-md-block paddingFix" >
            <h3 class="medium-title">Filters</h3>
            <?php if(isset($_GET['filter'])){ ?>
              <?php $blockName = str_replace("_"," ",$_GET['filter']); ?>
              <span class="filter-tag"><?=ucwords($blockName)?></span>
              <form style="display: inline-block;">
              <button class="delete-tag btn" type="submit" name="removeFilter">
                <i class="fas fa-times"></i>
              </button>
            </form>
            <?php } else { ?>  
              <div style="padding-bottom:0px"></div> 
            <?php } ?>
            <p style="margin-bottom:0px">Filter By Block</p>
            <form method="get" style="padding-bottom:25px" action="<?=$url?>palettes">
            <div class="input-group">
                <select id="select-1" name="filter" class="form-control" placeholder="Search a block..." required> 
                <option value="" class="cursor">Select a block...</option>
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
            <div align="center">
              <i class="fas fa-bell"></i> <i class="subText">More Filters Coming Soon</i>
            </div>
          </div>


          <?php if($results == null){ ?>
            <?php $blockName = str_replace("_"," ",$_GET['filter']); ?>
            <div class="col-xl-9 col-lg-8 col-md-12" style="padding-bottom:200px">
            <div class="row">
            <div class="col-lg-12">
              <h4 class="medium-title">Oh No!</h4>
              There are currently no palettes that contain <?=ucwords($blockName)?>.<br>
              Create your own <a href="submit">here</a>.
            </div>
           
          <?php } else { ?>
            <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="row" id="post-data">
              <?php foreach($results as $p): ?>
              <div class="col-xl-4 col-lg-6 col-md-6 paddingFix" id="<?=$p['id'];?>">
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
                  </div>
                </a>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php } ?>
         

          
          <div class="col-xl-3 col-lg-4 d-lg-block d-md-none d-sm-none">
            <h3 class="medium-title">Filters</h3>
            <?php if(isset($_GET['filter'])){ ?>
              <?php $blockName = str_replace("_"," ",$_GET['filter']); ?>
              <span class="filter-tag"><?=ucwords($blockName)?></span>
              <form style="display: inline-block;">
              <button class="delete-tag btn" type="submit" name="removeFilter">
                <i class="fas fa-times"></i>
              </button>
            </form>
            <?php } else { ?>  
              <div style="padding-bottom:0px"></div> 
            <?php } ?>
              <?php if(isset($_SESSION['dateFilter'])){ ?>
                  <?php $name = $_SESSION['dateFilter']; ?>
                  <span class="filter-tag"><?=ucwords($name)?></span>
                  <form style="display: inline-block;">
                      <button class="delete-tag btn" type="submit" name="removeFilter">
                          <i class="fas fa-times"></i>
                      </button>
                  </form>
              <?php } else { ?>
                  <div style="padding-bottom:0px"></div>
              <?php } ?>
            <p style="margin-bottom:0px">Filter By Block</p>
            <form method="get" style="padding-bottom:25px" action="<?=$url?>palettes">
              <div class="input-group">
                  <select id="select-1" name="filter" class="form-control" placeholder="Search a block..." required> 
                  <option value="" class="cursor">Select a block...</option>
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
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
      $(document).on('click', 'a', function() {

      var scrollpos = $(window).scrollTop(); 
      localStorage.setItem('scrollpos', scrollpos);
    });
    var scrollplan = function() {
      var foo = true
      if ((foo == true) && $('.palettes').length > 30) {
          var bar = localStorage.getItem('scrollpos')
          $("html, body").animate({ scrollTop: bar}, 500);
      }

      };
    </script>
    <script>
      $(window).scroll(function() {
      if(($(window).scrollTop() == $(document).height() - $(window).height())) { //Add in condition
          var last_id = $(".paddingFix:last").attr("id");
          loadMoreData(last_id);
      }
      });

      function loadMoreData(last_id){
      $.ajax({
          url: 'showMoreData.php?last_id=' + last_id,
          type: "get",
          beforeSend: function()
          {
              $('.ajax-load').hide();
          }
      })
      .done(function(data)
      {
              $('.ajax-load').show();
              $("#post-data").append(data);
          
      })
      .fail(function(jqXHR, ajaxOptions, thrownError)
      {
              alert('No response...');

      });
      }
    </script>
  </body>
</html>