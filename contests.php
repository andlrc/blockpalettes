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
    <title>Block Palettes - Contests</title>
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
            <div class="title" style="padding-bottom:5px">Contests</div>
            <p style="padding-bottom:25px">Win cool awards by creating palettes in specific contest themes.</p>
          </div>
         
          <div class="col-xl-3 col-lg-4 col-md-6 paddingFix">
            <div style="position: relative">
                <div class="palette-float" style="padding-right: 10px">
                <a href="<?=$url?>palette/<?=$p['id']?>" style="text-decoration: none">
                  <span id="clockdiv" class="contest-time-float-small" data-toggle="tooltip" data-placement="top" title="Time left in contest">
                      <i class="far fa-clock"></i> 
                        <span class="days"></span>:
                        <span class="hours"></span>:
                        <span class="minutes"></span>:
                        <span class="seconds"></span>
                    </span>
                  <img src="https://i.imgur.com/kcSdxHq.jpeg" class="contest-thumbnail">
                </a>
                  <div class="subtext">
                    <div class="contest-small-title left half">
                        Title of Contest
                    </div>
                    <div class="time right half">
                      <i class="far fa-user"></i> 345
                    </div>
                  </div>
                </div>
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

        const deadline = "Sun Apr 17 2021 13:55:56 GMT-0400 (EDT)";
        initializeClock('clockdiv', deadline);
          
    </script>
  </body>
</html>