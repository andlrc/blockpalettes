<?php 

//pull palettes
$paletteFooter = $pdo->prepare("SELECT * FROM palette ORDER BY date DESC LIMIT 2");
$paletteFooter->execute();
$pFooter = $paletteFooter->fetchAll(PDO::FETCH_ASSOC);

?>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="line"></div>
            <div class="col-lg-6" style="margin-top:-10px; padding-top:50px">
                <img src="../../img/logotest.png" class="logo-size" style="padding-bottom:20px">
                <p>We help Minecraft players find eye pleasing palettes to build with as well as create a place to connect with monthly building contest and showcases of the amazing things people build!</p>
                <h4 class="footer-title" style="padding-top:0px">Follow Us</h4>
                <a href="https://twitter.com/blockpalettes"><i class="fab fa-twitter fa-2x social"></i></a>
                <a href="https://www.instagram.com/blockpalettes/"><i class="fab fa-instagram fa-2x social"></i></a>
                <a href="https://www.buymeacoffee.com/blockpalettes"><i class="fas fa-heart fa-2x social"></i></a>
                
            </div>
            <div class="col-lg-2" style=" padding-top:50px">
                <h4 class="footer-title">Browse Palettes</h4>
                <ul>
                    <li class="footerText"><a href="../../popular">Popular</a></li>
                    <li class="footerText"><a href="../../new">New</a></li>
                    <li class="footerText"><a href="../../submit">Submit</a></li>
                </ul>
            </div>
            <div class="col-lg-4" style=" padding-top:50px; padding-bottom:100px">
                <h4 class="footer-title">Recent Palettes</h4>
                <div class="row">
                    <?php foreach($pFooter as $p): ?>
                    <div class="col-lg-6 col-6">
                        <div style="position: relative">
                            <a href="<?=$url?>palette/<?=$p['id']?>">
                                <div class="palette-float" style="padding-bottom:10px">
                                <img src="../../img/block/<?=$p['blockOne']?>.png" class="block">
                                <img src="../../img/block/<?=$p['blockTwo']?>.png" class="block">
                                <img src="../../img/block/<?=$p['blockThree']?>.png" class="block">
                                <img src="../../img/block/<?=$p['blockFour']?>.png" class="block">
                                <img src="../../img/block/<?=$p['blockFive']?>.png" class="block">
                                <img src="../../img/block/<?=$p['blockSix']?>.png" class="block">
                                <div class="subtext">
                                    
                                </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</footer>