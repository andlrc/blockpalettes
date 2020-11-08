<?php
session_start();
require "include/connect.php";
require "include/logic.php";

$id = !empty($_GET['p']) ? trim($_GET['p']) : null;
$pid = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

//pull palette
$pPull = $pdo->prepare("SELECT * FROM palette WHERE id = $pid");
$pPull->execute();
$pf = $pPull->fetch(PDO::FETCH_ASSOC);

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
    <title>Block Palettes - Trendy Block Palettes</title>
    <meta name="description" content="We help Minecraft players find eye pleasing palettes to build with as well as create a place to connect with submitting your own palettes and monthly building contest!">
  	<meta name="keywords" content="Minecraft, Building, Blocks, Colors, Creative, <?=$blockOne?>, <?=$blockTwo?>, <?=$blockThree?>, <?=$blockFour?>, <?=$blockFive?>, <?=$blockSix?>">
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
    <div class="custom-header" id="#">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?=$url?>">
                    <img src="../img/logotest.png" class="logo-size">
                </a>
                <button class="navbar-toggler custom-toggler" id="hamburger" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="images/hamburger.png" width="35px">
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav ml-auto custom-nav-text centeredContent">
                      <li class="nav-item">
                            <a href="<?=$url?>" class="nav-link">Featured Palettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=$url?>new" class="nav-link">New Palettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=$url?>create" class="nav-link btn btn-theme-nav">Create</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!-- End Nav -->
    <div class="palettes">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-12 paddingFix-large">
                    <div style="position: relative">
                        <div class="palette-float-large">
                            <img src="../img/block/<?=$pf['blockOne']?>.png" class="block">
                            <img src="../img/block/<?=$pf['blockTwo']?>.png" class="block">
                            <img src="../img/block/<?=$pf['blockThree']?>.png" class="block">
                            <img src="../img/block/<?=$pf['blockFour']?>.png" class="block">
                            <img src="../img/block/<?=$pf['blockFive']?>.png" class="block">
                            <img src="../img/block/<?=$pf['blockSix']?>.png" class="block">
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="palette-float-info">
                        <h2 class="medium-title">Palette #<?=$pf['id']?></h2>
                        <div class="subtext">
                            <?php if($pf['featured'] == 1) { ?>
                            <div class="award half">
                                <i class="fas fa-award"></i> Staff Pick
                                </div>
                                <div class="time half">
                                <?=time_elapsed_string($pf['date'])?>
                            </div>
                            <?php } else { ?>
                            <div class="time" style="float:left">
                             <?=time_elapsed_string($pf['date'])?>
                            </div>
                            <?php } ?>
                        </div>
        
                                       
                        <div></div>
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
                <?php foreach($palette as $p): ?>
                <div class="col-xl-4 col-lg-6 col-sm-6 paddingFix">
                    <div style="position: relative">
                        <a href="<?=$p['id']?>">
                            <div class="palette-float">
                                <img src="../img/block/<?=$p['blockOne']?>.png" class="block">
                                <img src="../img/block/<?=$p['blockTwo']?>.png" class="block">
                                <img src="../img/block/<?=$p['blockThree']?>.png" class="block">
                                <img src="../img/block/<?=$p['blockFour']?>.png" class="block">
                                <img src="../img/block/<?=$p['blockFive']?>.png" class="block">
                                <img src="../img/block/<?=$p['blockSix']?>.png" class="block">
                                <div class="subtext">
                                    <?php if($p['featured'] == 1) { ?>
                                    <div class="award half">
                                        <i class="fas fa-award"></i> Staff Pick
                                        </div>
                                        <div class="time half">
                                        <?=time_elapsed_string($p['date'])?>
                                    </div>
                                    <?php } else { ?>
                                    <div class="time" style="float:right">
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
    </div>

    <?php include('include/footer.php') ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
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
            <li>You can now create palettes in real time! Head over to the <a href="<?=$url?>/create">create</a> page and create a beautiful block palette.</li>
            <li>We are still curating palettes. On the <a href="<?=$url?>">featured</a> page our staff picks 12 users submitted palettes every week to be apart of the collection!</li>
            <li>We have created an <a href="https://www.instagram.com/blockpalettes/">Instagram</a> where we will post daily palettes from the <a href="<?=$url?>/new">new palettes</a> page.</li>
            </ul>
            <p>This is just the beginning with this new platform. We have many great updates on the way that will continue to improve the site into the future!</p>
            <p>Thank you for the support!<br><i>- Block Palettes Staff</i></p>
          </div>    
        </div>
      </div>
    </div>
  </body>
</html>