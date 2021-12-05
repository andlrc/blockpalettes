<?php 

session_start();
require "include/logic.php";


$token = $_GET['token'];

$sql = "SELECT COUNT(*) as num FROM email_verify WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    //Bind variables
    $stmt->bindValue(':token', $token);
    $stmt->execute();
    //Fetch the row
    $tokenCheck = $stmt->fetch(PDO::FETCH_ASSOC);


if($tokenCheck['num'] == 0){
    header('Location:' . $url);
    exit;
 }



    $token = $_GET['token'];

    $sql = "SELECT * FROM email_verify WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    //Bind variables
    $stmt->bindValue(':token', $token);
    $stmt->execute();
    //Fetch the row
    $usernameCheck = $stmt->fetch(PDO::FETCH_ASSOC);

    $emailtoken = $usernameCheck['email'];

    //Username or email already exists error
    if($usernameCheck['token'] !== null) {
        
        $userUpdate = "UPDATE user SET verified = true WHERE email = '$emailtoken'";
        $update = $pdo->prepare($userUpdate);
        $resultuser = $update->execute();

        $delete = "DELETE FROM email_verify WHERE email = :email AND token = :token";
        $stmt = $pdo->prepare($delete);
        //Bind varibles
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':email', $emailtoken);

        //Execute the statement
        $result = $stmt->execute();

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
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <title>Block Palettes - Set New Password</title>
    <meta name="description" content="We help Minecraft players find eye pleasing palettes to build with as well as create a place to connect with submitting your own palettes and monthly building contest!">
  
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
    <?php include('include/header.php'); ?>
	<div class="row">
		<div class="container-fluid" style="min-height:50vh">
			<div class="col-lg-6 offset-lg-3 col-md-12">
				<div class="reset-panel">
            <h2 class="medium-title">Account Verified!</h2>
            <p>Thank you for verifying your email. You can now create, save and browse palettes!</p>
            <a href="<?=$url?>" class="btn btn-theme btn-block">Navigate to Site</a>
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
    </script>
  </body>
  
</html>