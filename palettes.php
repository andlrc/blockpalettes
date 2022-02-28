<?php
error_reporting(1);
session_start();
include "include/logic.php";


if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])){
  $uid = $_SESSION['user_id'];
  $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} 


//pagination
  $block = $_GET['block'];
  $s = $_GET['s'];

  $limit = 21;

  if((!isset($block)) && (!isset($s))){
    $palettePull = $pdo->prepare("SELECT * FROM palette ORDER BY id DESC");
    $palettePull->execute();


    $total_results = $palettePull->rowCount();
    $total_pages = ceil($total_results/$limit);
        
    if (!isset($_GET['p'])) {
        $page = 1;
    } else{
        $page = htmlspecialchars(addslashes($_GET['p']));
    }

    $start = ($page-1)*$limit;

    $stmt = $pdo->prepare("SELECT * FROM palette WHERE hidden = 0 ORDER BY id DESC LIMIT $start, $limit");
    $stmt->execute();

  } elseif((isset($block)) && (isset($s))){
    if($s == "popular"){
      $s = "ORDER BY likes DESC";
    } elseif($s == "old"){
      $s = "ORDER BY date ASC";
    } elseif($s == "trending"){
      $term = "trending";
    } else{
      $s = "ORDER BY date DESC";
    }
    $block = "blockOne LIKE '$block' 
                      OR blockTwo LIKE '$block' 
                      OR blockThree LIKE '$block' 
                      OR blockFour LIKE '$block' 
                      OR blockFive LIKE '$block' 
                      OR blockSix LIKE '$block'";

    
    if($term == "trending"){
      $palettePull = $pdo->prepare("SELECT * FROM palette WHERE $block");
    } else {
      $palettePull = $pdo->prepare("SELECT * FROM palette WHERE $block $s");
    }
    $palettePull->execute();

    $total_results = $palettePull->rowCount();
    $total_pages = ceil($total_results/$limit);
    
    if (!isset($_GET['p'])) {
        $page = 1;
    } else{
        $page = htmlspecialchars(addslashes($_GET['p']));
    }

    $start = ($page-1)*$limit;

    if($term == "trending"){
      $stmt = $pdo->prepare("SELECT *, DATEDIFF(CURRENT_TIMESTAMP,date) AS days FROM palette WHERE hidden = 0 AND $block ORDER BY LOG10(ABS(likes - days) + 1) * SIGN(likes - days) + (UNIX_TIMESTAMP(date) / 1000000) DESC LIMIT $start, $limit"); 
    } else {
      $stmt = $pdo->prepare("SELECT * FROM palette WHERE hidden = 0 AND $block $s LIMIT $start, $limit");
    }
    $stmt->execute();

  } elseif(isset($block)){
    $block = "blockOne LIKE '$block' 
              OR blockTwo LIKE '$block' 
              OR blockThree LIKE '$block' 
              OR blockFour LIKE '$block' 
              OR blockFive LIKE '$block' 
              OR blockSix LIKE '$block'";

    $palettePull = $pdo->prepare("SELECT * FROM palette WHERE $block");
    $palettePull->execute();


    $total_results = $palettePull->rowCount();
    $total_pages = ceil($total_results/$limit);

    if (!isset($_GET['p'])) {
    $page = 1;
    } else{
    $page = htmlspecialchars(addslashes($_GET['p']));
    }

    $start = ($page-1)*$limit;

  
    $stmt = $pdo->prepare("SELECT * FROM palette WHERE hidden = 0 AND $block LIMIT $start, $limit");
    $stmt->execute();

  } elseif(isset($s)){
    if($s == "popular"){
      $s = "ORDER BY likes DESC";
    } elseif($s == "old"){
      $s = "ORDER BY date ASC";
    } elseif($s == "trending"){
      $term = "trending";
      $select = "SELECT *, DATEDIFF(CURRENT_TIMESTAMP,date) AS days FROM palette ORDER BY LOG10(ABS(likes - days) + 1) * SIGN(likes - days) + (UNIX_TIMESTAMP(date) / 1000000) DESC ";
    } else{
      $s = "ORDER BY date DESC";
    }
   
      if($term == "trending"){
        $palettePull = $pdo->prepare("SELECT * FROM palette");
      } else {
        $palettePull = $pdo->prepare("SELECT * FROM palette $s");
      }
      $palettePull->execute();


      $total_results = $palettePull->rowCount();
      $total_pages = ceil($total_results/$limit);
          
      if (!isset($_GET['p'])) {
          $page = 1;
      } else{
          $page = htmlspecialchars(addslashes($_GET['p']));
      }

      $start = ($page-1)*$limit;

      if($term == "trending"){
        $stmt = $pdo->prepare("$select LIMIT $start, $limit"); 
      } else {
        $stmt = $pdo->prepare("SELECT * FROM palette WHERE hidden = 0 $s LIMIT $start, $limit");
      }
      $stmt->execute();

  }

  // set the resulting array to associative
  $stmt->setFetchMode(PDO::FETCH_OBJ);
        
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
  $conn = null;

  // var_dump($results);
  $no = $page > 1 ? $start+1 : 1;

  $i = 0;

  $total_pages = $total_pages - 1;


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


$dir = "img/block/*.png";
//get the list of all files with .jpg extension in the directory and safe it in an array named $images
$images = glob( $dir );


//post request to check which ones are set, if one is set pull data, if two are set have if statement in each one.

$path = $_SERVER['REQUEST_URI'];

if(empty($_GET)){
  $uri = $path . '?';
} else {
  $uri = $path . '&';
}


$filtered_get = array_filter($_GET); // removes empty values from $_GET

$selected = $_GET; 
$sblock = $selected['block'];
$stime = $selected['s'];

$sFilter = array("s" => array("trending","popular","old","new"));





?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Test -->
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
    <script data-ad-client="ca-pub-9529646541661119" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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

          <!--Filters-->
          <div class="col-md-12 d-xl-none d-lg-none d-md-block paddingFix" >
            <h3 class="medium-title">Filters</h3>
            <?php if(!empty($_GET)){ ?>
              <?php if(isset($_GET['p'])) {
                if((isset($_GET['block'])) || (isset($_GET['s']))) { 
                  echo '<div style="padding-bottom:10px">
                          <a href="palettes">
                            <span class="delete-tag">
                              Clear
                              <i class="fas fa-times"></i>
                            </span>
                          </a>
                        </div>';
                } else {

                }
              } else {
                echo '<div style="padding-bottom:10px">
                        <a href="palettes">
                          <span class="delete-tag">
                            Clear
                            <i class="fas fa-times"></i>
                          </span>
                        </a>
                      </div>';
              }

              ?>
  
              <?php 
             
              $i = 0;
                $selected_filters = array_filter($_GET);
                foreach($filtered_get as $key => $value):
                  $filter = str_replace("_"," ",$value);
                  
              ?>
              <?php if($key == "p"){ ?>
                   
              <?php } else { ?>
                <span class="filter-tag">
                  <?=ucwords($filter)?>
                </span>
              <?php } ?>
              <?php endforeach; ?>
          
            <?php } ?>
            <p style="margin-bottom:0px">Filter By Block</p>
              <div class="input-group" style="padding-bottom:25px">
                  <select id="select-1" name="blockmobile" class="form-control" placeholder="Search a block..." required> 
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
                  <a class="btn-filter btn" id="resultsmobile" href=""><i class="fas fa-search"></i></a>
                </div>

            <p style="margin-bottom:0px">Sort By</p>
              <?php foreach($sFilter['s'] as $tfilter): ?>
                  <?php 
                    if(empty($_GET)){
                      $uri = $path . '?';
                    } else {
                      $uri = $path . '&';
                    }

                    //Remove current page in url
                    $current_page = $_GET['p'];
                    if(strpos($uri, '?p=' . $current_page) !== false){
                      $uri = str_replace('p=' . $current_page . '&', "", $uri);
                    } elseif(strpos($uri, "&p=".$current_page) !== false){
                      $uri = str_replace('&p=' . $current_page, "", $uri);
                    }

                    if(strpos($uri, '?s=' . $stime) !== false){
                      $uri = str_replace('s=' . $stime . '&', "", $uri);
                    } elseif(strpos($uri, "&s=".$stime) !== false){
                      $uri = str_replace('&s=' . $stime, "", $uri);
                    }

                   
                  ?>
                  <a class="block-pill" href="<?=$uri.'s=' . $tfilter?>">
                    <b><?=ucwords($tfilter)?></b>
                  </a>
                <?php endforeach; ?>
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
              </div>
            </div>
            
           
          <?php } else { ?>
            <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="row">
          <?php foreach($results as $p): ?>
          <div class="col-xl-4 col-lg-6 col-md-6 paddingFix">
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
          
          

          <?php
              $pgStart = 1;
              $pg = $_GET['p'] - 2;
              $pgStart = $pg + 5 > $total_pages ? $total_pages - 4 : $pg; //EDIT fix when reach pages end
              $pgStart = $pg < 1 ? 1 : $pg; // This must be after ending correction (previous line)
            ?>
            
              <nav aria-label="Page navigation example">
                  <ul class="pagination justify-content-center">
                      <?php if ($pgStart > 1) { // show 1 ?>
                      <?php
                          if(empty($_GET)){
                            $uri = $path . '?';
                          } else {
                            $current_page = $_GET['p'];
                            $uri = $path . '&';
                          }
  
                          if(strpos($uri, '?p=') !== false){
                            $uri = str_replace('p=' . $current_page . '&', "", $uri);
                          } elseif(strpos($uri, "&p=") !== false){
                            $uri = str_replace('&p=' . $current_page, "", $uri);
                          }  
                      ?>
                      <li class="page-item"><a href="<?=$uri.'p=1'?>" class="page-link">1</a></li>
                       <li class="page-item"><a href="<?=$uri.'p=1'?>" class="page-link">...</a></li>
                      <?php } ?>
                      <?php for($e = $pgStart; $e <= $total_pages && $e < $pgStart + 5; $e++){?>
                      <?php 
                        if(empty($_GET)){
                          $uri = $path . '?';
                        } else {
                          $current_page = $_GET['p'];
                          $uri = $path . '&';
                        }

                        if(strpos($uri, '?p=') !== false){
                          $uri = str_replace('p=' . $current_page . '&', "", $uri);
                        } elseif(strpos($uri, "&p=") !== false){
                          $uri = str_replace('&p=' . $current_page, "", $uri);
                        }      
                      ?>
                      <li class="<?= $page == $e ? 'active' : ''; ?> page-item"><a href="<?=$uri.'p=' . $e?>" class="page-link"><?= $e; ?></a></li>
                      <?php }?>
                      <?php if ($e < $total_pages) { ?>
                      <?php 
                        if(empty($_GET)){
                          $uri = $path . '?';
                        } else {
                          $current_page = $_GET['p'];
                          $uri = $path . '&';
                        }

                        if(strpos($uri, '?p=') !== false){
                          $uri = str_replace('p=' . $current_page . '&', "", $uri);
                        } elseif(strpos($uri, "&p=") !== false){
                          $uri = str_replace('&p=' . $current_page, "", $uri);
                        }      
                      ?>
                          <li class="page-item"><a href="<?=$uri.'p=' . $total_pages?>" class="page-link">...</a></li>
                      <li class="page-item"><a href="<?=$uri.'p=' . $total_pages?>" class="page-link"><?=$total_pages?></a></li>
                      <?php } ?>
                  </ul> 
              </nav>
          </div>
          <?php } ?>
          

          <!--Filters-->
          <div class="col-xl-3 col-lg-4 d-lg-block d-md-none d-sm-none">
            <h3 class="medium-title">Filters</h3>
            <?php if(!empty($_GET)){ ?>
              <?php if(isset($_GET['p'])) {
                if((isset($_GET['block'])) || (isset($_GET['s']))) { 
                  echo '<div style="padding-bottom:10px">
                          <a href="palettes">
                            <span class="delete-tag">
                              Clear
                              <i class="fas fa-times"></i>
                            </span>
                          </a>
                        </div>';
                } else {

                }
              } else {
                echo '<div style="padding-bottom:10px">
                        <a href="palettes">
                          <span class="delete-tag">
                            Clear
                            <i class="fas fa-times"></i>
                          </span>
                        </a>
                      </div>';
              }

              ?>
  
              <?php 
             
              $i = 0;
                $selected_filters = array_filter($_GET);
                foreach($filtered_get as $key => $value):
                  $filter = str_replace("_"," ",$value);
                  
              ?>
              <?php if($key == "p"){ ?>
                   
              <?php } else { ?>
                <span class="filter-tag">
                  <?=ucwords($filter)?>
                </span>
              <?php } ?>
              <?php endforeach; ?>
          
            <?php } ?>
            <p style="margin-bottom:0px">Filter By Block</p>
              <div class="input-group" style="padding-bottom:25px">
                  <select id="select-1" name="block" class="form-control" placeholder="Search a block..." required> 
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
                  <a class="btn-filter btn" id="results" href=""><i class="fas fa-search"></i></a>
                </div>

            <p style="margin-bottom:0px">Sort By</p>
              <?php foreach($sFilter['s'] as $tfilter): ?>
                  <?php 
                    if(empty($_GET)){
                      $uri = $path . '?';
                    } else {
                      $uri = $path . '&';
                    }

                    //Remove current page in url
                    $current_page = $_GET['p'];
                    if(strpos($uri, '?p=' . $current_page) !== false){
                      $uri = str_replace('p=' . $current_page . '&', "", $uri);
                    } elseif(strpos($uri, "&p=".$current_page) !== false){
                      $uri = str_replace('&p=' . $current_page, "", $uri);
                    }

                    if(strpos($uri, '?s=' . $stime) !== false){
                      $uri = str_replace('s=' . $stime . '&', "", $uri);
                    } elseif(strpos($uri, "&s=".$stime) !== false){
                      $uri = str_replace('&s=' . $stime, "", $uri);
                    }

                   
                  ?>
                  <a class="block-pill" href="<?=$uri.'s=' . $tfilter?>">
                    <b><?=ucwords($tfilter)?></b>
                  </a>
                <?php endforeach; ?>
            <p style="margin-bottom:0px; padding-top:25px">Popular Blocks</p>
              <?php foreach($t as $popular): ?>
                <?php $block = str_replace("_"," ",$popular['blocks']); ?>   
                <?php
                    if(empty($_GET)){
                      $uri = $path . '?';
                    } else {
                      $uri = $path . '&';
                    }
            
                    //Remove current page in url
                    $current_page = $_GET['p'];
                    if(strpos($uri, '?p=' . $current_page) !== false){
                      $uri = str_replace('p=' . $current_page . '&', "", $uri);
                    } elseif(strpos($uri, "&p=".$current_page) !== false){
                      $uri = str_replace('&p=' . $current_page, "", $uri);
                    }

                    if(strpos($uri, '?block=' . $sblock) !== false){
                      $uri = str_replace('block=' . $sblock . '&', "", $uri);
                    } elseif(strpos($uri, "&block=".$sblock) !== false){
                      $uri = str_replace('&block=' . $sblock, "", $uri);
                    }

                ?>
                  <a class="block-pill" href="<?=$uri . 'block=' . $popular['blocks']?>">
                    <img src="<?=$url?>img/block/<?=$popular['blocks']?>.png"> <b><?=ucwords($block)?></b><br>
                  </a>
              <?php endforeach; ?>

              <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9529646541661119"
              crossorigin="anonymous"></script>
              <!-- Sidebar Ad -->
              <ins class="adsbygoogle"
                  style="display:block"
                  data-ad-client="ca-pub-9529646541661119"
                  data-ad-slot="7681853473"
                  data-ad-format="auto"
                  data-full-width-responsive="true"></ins>
              <script>
                  (adsbygoogle = window.adsbygoogle || []).push({});
              </script>

          </div>
        </div>
      </div>
    </div>

    <?php include('include/footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script>
      $('select[name="block"]').on('change', function(){    
          var selectedVar = $('select[name="block"]').val();   
          var pathname = window.location.href;
          
          var $_GET = {};

          document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
              function decode(s) {
                  return decodeURIComponent(s.split("+").join(" "));
              }

              $_GET[decode(arguments[1])] = decode(arguments[2]);
          });

          var currentPage = $_GET["p"];
          var currentBlock = $_GET["block"];
          var newBlock = "block=" + selectedVar;
 
          if (currentBlock == null){
            if (pathname.includes("s=")){
              pathname = pathname + "&" + newBlock;
            } else {
              pathname = pathname + "?" + newBlock;
            }
            
            if (pathname.includes("?p=")){
              pathname = pathname.replace("?p="+currentPage, "");
            } else if (pathname.includes("&p=")){
              pathname = pathname.replace("&p="+currentPage, "");
            }
          } else {
            pathname = pathname.replace("block="+currentBlock, newBlock);
            if (pathname.includes("?p=")){
              pathname = pathname.replace("?p="+currentPage, "");
            } else if (pathname.includes("&p=")){
              pathname = pathname.replace("&p="+currentPage, "");
            }
          }
     
          $('#results').attr("href", pathname);

      });
    </script>

<script>
      $('select[name="blockmobile"]').on('change', function(){    
          var selectedVar = $('select[name="blockmobile"]').val();   
          var pathname = window.location.href;
          
          var $_GET = {};

          document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
              function decode(s) {
                  return decodeURIComponent(s.split("+").join(" "));
              }

              $_GET[decode(arguments[1])] = decode(arguments[2]);
          });

          var currentPage = $_GET["p"];
          var currentBlock = $_GET["block"];
          var newBlock = "block=" + selectedVar;
 
          if (currentBlock == null){
            if (pathname.includes("s=")){
              pathname = pathname + "&" + newBlock;
            } else {
              pathname = pathname + "?" + newBlock;
            }
            
            if (pathname.includes("?p=")){
              pathname = pathname.replace("?p="+currentPage, "");
            } else if (pathname.includes("&p=")){
              pathname = pathname.replace("&p="+currentPage, "");
            }
          } else {
            pathname = pathname.replace("block="+currentBlock, newBlock);
            if (pathname.includes("?p=")){
              pathname = pathname.replace("?p="+currentPage, "");
            } else if (pathname.includes("&p=")){
              pathname = pathname.replace("&p="+currentPage, "");
            }
          }
     
          $('#resultsmobile').attr("href", pathname);

      });
    </script>
    
    <script>
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
      <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
      </script>
  </body>
</html>