<?php 
include "include/logic.php";
$lastid = $_GET['last_id'];
$pagData = $pdo->query("SELECT * FROM palette WHERE id < $lastid ORDER BY id DESC LIMIT 15");
$pagData->setFetchMode(PDO:: FETCH_ASSOC);

while($p = $pagData->fetch()) { 
?>

<div class="col-xl-4 col-lg-6 col-md-6 paddingFix"  id="<?=$p['id'];?>">
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


<?php
 }  
?>