<?php 

require "password.php";
require "connect.php";

$url = "http://localhost/blockpalettes/";

//Register a user
if(isset($_POST['register'])){
    $error = "";
    //Retrieves input data
    $username = htmlspecialchars(addslashes($_POST['username']));
    $email = htmlspecialchars(addslashes($_POST['email']));
    $password = htmlspecialchars(addslashes($_POST['password']));
    //Checking if the supplied username/email already exists
    //Preparing SQL statement
    $sql = "SELECT COUNT(*) AS email FROM user WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    //Bind variables
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    //Fetch the row
    $emailCheck = $stmt->fetch(PDO::FETCH_ASSOC);

    //Username or email alreay exists error
    if($emailCheck['email'] > 0) {
        $error = 'That email is already in use!';
        $_SESSION['emailError'] = "error";
        header('Location: ' . $url . '');
        exit;
    }

    $sql = "SELECT COUNT(*) AS user FROM user WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    //Bind variables
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    //Fetch the row
    $usernameCheck = $stmt->fetch(PDO::FETCH_ASSOC);

    //Username or email alreay exists error
    if($usernameCheck['user'] > 0) {
        $error = 'That username is already in use!';
        $_SESSION['userError'] = "error";
        header('Location: ' . $url . '');
        exit;
    }

    //Hashing the password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

    //Preparing insert statement
    $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    //Bind variables
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $passwordHash);
    //Execute
    $result = $stmt->execute();

    //If register was successful
    if($result) {

          // Send email to user with the token in a link they can click on
        $to = $email;
        $subject = "Welcome to Block Palettes!";
        $msg = "Hi there, <br>Thank you for creating an account on our website! Create your own block palettes or browse hundreds of community made palettes!<br>- Block Palettes Staff";
        $msg = wordwrap($msg,70);
        $headers .= "Organization: Block Palettes\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $headers .= "Reply-To: Block Palettes <hello@blockpalettes>\r\n";
        $headers .= "Return-Path: Block Palettes <hello@blockpalettes>\r\n";
        $headers .= "From: Block Palettes <hello@blockpalettes.com>\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
        mail($to, $subject, $msg, $headers);
        $_SESSION['email'] = $email;
        $_SESSION['token'] = $token;
        header('location: ' . $url . 'pending?email=' . $email);



        $_SESSION['userRegister'] = "success";
        header('Location: ' . $url . '');
    }
}

if(isset($_POST['login'])){
    $error = '';
    //Retrieves input data
    $usernameEmail = htmlspecialchars(addslashes($_POST['email']));
    $passwordAttempt = htmlspecialchars(addslashes($_POST['password']));

    //Retrieves the user account information for the given username/email.
    $sql = "SELECT id, email, password FROM user WHERE email = :usernameEmail";
    $stmt = $pdo->prepare($sql);
    //Bind variable
    $stmt->bindValue(':usernameEmail', $usernameEmail);
    //Execute
    $stmt->execute();
    //Fetch row
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //If username/email was false
    if($user === false){
        //No user with that username/email
        $error = 'We could not find a user with that username or email';
     } else {
        //User account found. Check to see if passwords match

        //Compare the passwords
        $validPassword = password_verify($passwordAttempt, addslashes(htmlspecialchars($user['password'])));

        //If $validPassword is TRUE, the login is successful
        if($validPassword){
            //Provide the user with a LOGIN session.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['logged_in'] = time();
            //Redirect user
            header('Location: ' . $url . '');
            exit;
        } else {
            //$validPassword was FALSE. Passwords didnt match
            $error = 'Password was incorrect';
        }
    }
}

// Functions


function time_elapsed_string($datetime, $full = false) {
    date_default_timezone_set("America/Los_Angeles");
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Lf0ouAZAAAAAIk1Rkh-sda3QaTDN0lVXETByFWr';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score >= 0.5) {
        if(isset($_POST['create'])){

            //Sanitizes data
            $blockOne = !empty($_POST['block-one']) ? trim($_POST['block-one']) : null;
            $blockTwo = !empty($_POST['block-two']) ? trim($_POST['block-two']) : null;
            $blockThree = !empty($_POST['block-three']) ? trim($_POST['block-three']) : null;
            $blockFour = !empty($_POST['block-four']) ? trim($_POST['block-four']) : null;
            $blockFive = !empty($_POST['block-five']) ? trim($_POST['block-five']) : null;
            $blockSix = !empty($_POST['block-six']) ? trim($_POST['block-six']) : null;


            $one = htmlspecialchars($blockOne, ENT_QUOTES, 'UTF-8');
            $two = htmlspecialchars($blockTwo, ENT_QUOTES, 'UTF-8');
            $three = htmlspecialchars($blockThree, ENT_QUOTES, 'UTF-8');
            $four = htmlspecialchars($blockFour, ENT_QUOTES, 'UTF-8');
            $five = htmlspecialchars($blockFive, ENT_QUOTES, 'UTF-8');
            $six = htmlspecialchars($blockSix, ENT_QUOTES, 'UTF-8');

            $uid = $_POST['uid'];


            $oneCut = str_replace(".png","","$one");
            $oneCleanStr = str_replace("img/block/","","$oneCut");

            $twoCut = str_replace(".png","","$two");
            $twoCleanStr = str_replace("img/block/","","$twoCut");

            $threeCut = str_replace(".png","","$three");
            $threeCleanStr = str_replace("img/block/","","$threeCut");

            $fourCut = str_replace(".png","","$four");
            $fourCleanStr = str_replace("img/block/","","$fourCut");

            $fiveCut = str_replace(".png","","$five");
            $fiveCleanStr = str_replace("img/block/","","$fiveCut");

            $sixCut = str_replace(".png","","$six");
            $sixCleanStr = str_replace("img/block/","","$sixCut");





                //Checking if the supplied username already exists
            //Preparing SQL statement
            $sql = "SELECT COUNT(id) AS num FROM palette WHERE blockOne LIKE '$oneCleanStr' 
                                            AND blockTwo LIKE '$twoCleanStr' 
                                            AND blockThree LIKE '$threeCleanStr' 
                                            AND blockFour LIKE '$fourCleanStr' 
                                            AND blockFive LIKE '$fiveCleanStr' 
                                            AND blockSix LIKE '$sixCleanStr'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            //Fetch the row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //Username already exists error
            if($row['num'] > 0){
                session_start();
                $_SESSION['error'] = "error";
                header('Location: ' . $url . 'submit');
                exit();
            }

                //Preparing insert statement
                $sql = "INSERT INTO palette (uid, blockOne, blockTwo, blockThree, blockFour, blockFive, blockSix) VALUES (:uid, :blockOne, :blockTwo, :blockThree, :blockFour, :blockFive, :blockSix)";
                $stmt = $pdo->prepare($sql);
                //Bind varibles
                $stmt->bindValue(':uid', $uid);
                $stmt->bindValue(':blockOne', $oneCleanStr);
                $stmt->bindValue(':blockTwo', $twoCleanStr);
                $stmt->bindValue(':blockThree', $threeCleanStr);
                $stmt->bindValue(':blockFour', $fourCleanStr);
                $stmt->bindValue(':blockFive', $fiveCleanStr);
                $stmt->bindValue(':blockSix', $sixCleanStr);
            
                //Execute the statement
                $result = $stmt->execute();
            
                //If successful, returns to user profile
                if($result) {
                    $_SESSION['last_submit'] = time();
                    $_SESSION['create'] = "New Palette";
                    header('Location: ' . $url . 'palettes');
                }

        }
    } else {
        header('Location: ' . $url . 'palettes');
    }
}

//unfavorite
if(isset($_POST['unfavorite'])){
    $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

    //Updates table
    $edit = "UPDATE palette SET featured = 0 WHERE id ='$id'";
    $stmt = $pdo->prepare($edit);

    $result = $stmt->execute();

    //If successful, returns to user profile
    if($result) {
        header('Location: ' . $url . 'dashboard/palettes');
    }  
}

if(isset($_POST['favorite'])){
    $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

    //Updates table
    $edit = "UPDATE palette SET featured = 1 WHERE id ='$id'";
    $stmt = $pdo->prepare($edit);

    $result = $stmt->execute();

    //If successful, returns to user profile
    if($result) {
        header('Location: ' . $url . 'dashboard/palettes');
    }  
}

 // Delete
 if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $delete = "DELETE FROM palette WHERE id = :id";
    $stmt = $pdo->prepare($delete);
    //Bind varibles
    $stmt->bindValue(':id', $id);

    $result = $stmt->execute();

    //If follow was successful
    if($result) {
        header('Location: ' . $url . 'dashboard/palettes');
    }
}


function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '...';
    echo $y;
  }
}


//Blog post
if(isset($_POST['blog'])){

    //Sanitizes data
    $titleIn = !empty($_POST['title']) ? trim($_POST['title']) : null;
    $articleIn = !empty($_POST['article']) ? trim($_POST['article']) : null;
    $imageIn = !empty($_POST['image']) ? trim($_POST['image']) : null;
    $metaIn = !empty($_POST['meta']) ? trim($_POST['meta']) : null;
    $typeIn = !empty($_POST['type']) ? trim($_POST['type']) : null;


    $title = htmlspecialchars($titleIn, ENT_QUOTES, 'UTF-8');
    $article = htmlspecialchars($articleIn, ENT_QUOTES, 'UTF-8');
    $image = htmlspecialchars($imageIn, ENT_QUOTES, 'UTF-8');
    $meta = htmlspecialchars($metaIn, ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($typeIn, ENT_QUOTES, 'UTF-8');
    $uid = $_POST['uid'];

    $tLower = strtolower($title);

    //Checking if the supplied username already exists
    //Preparing SQL statement
    $sql = "SELECT COUNT(id) AS num FROM blog WHERE title = $tLower";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    //Fetch the row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Username already exists error
    if($row['num'] > 0){
        session_start();
        $_SESSION['error'] = "error";
        header('Location: ' . $url . 'submit');
        exit();
    }

        //Preparing insert statement
        $sql = "INSERT INTO blog (uid, title, article, image, meta, post_type) VALUES (:uid, :title, :article, :image, :meta, :type)";
        $stmt = $pdo->prepare($sql);
        //Bind varibles
        $stmt->bindValue(':uid', $uid);
        $stmt->bindValue(':title', $tlower);
        $stmt->bindValue(':article', $article);
        $stmt->bindValue(':image', $image);
        $stmt->bindValue(':meta', $meta);
        $stmt->bindValue(':type', $type);
    
        //Execute the statement
        $result = $stmt->execute();
    
        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/all-posts');
        }

 }

 if(isset($_POST['updateBlog'])){
    $titleIn = !empty($_POST['title']) ? trim($_POST['title']) : null;
    $articleIn = !empty($_POST['article']) ? trim($_POST['article']) : null;
    $imageIn = !empty($_POST['image']) ? trim($_POST['image']) : null;
    $metaIn = !empty($_POST['meta']) ? trim($_POST['meta']) : null;
    $typeIn = !empty($_POST['type']) ? trim($_POST['type']) : null;


    $titleTall = htmlspecialchars($titleIn, ENT_QUOTES, 'UTF-8');
    $article = htmlspecialchars($articleIn, ENT_QUOTES, 'UTF-8');
    $image = htmlspecialchars($imageIn, ENT_QUOTES, 'UTF-8');
    $meta = htmlspecialchars($metaIn, ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($typeIn, ENT_QUOTES, 'UTF-8');
    $id = $_POST['id'];

    $title = strtolower($titleTall);

    //Updates table
    $edit = "UPDATE blog SET title = '$title', article = '$article', image = '$image', meta = '$meta', post_type = '$type' WHERE id ='$id'";
    $stmt = $pdo->prepare($edit);

    $result = $stmt->execute();

    //If successful, returns to user profile
    if($result) {
        $_SESSION['success'] = "success";
        header('Location: ' . $url . 'dashboard/edit?p=' . $id);
        exit();
    }  
}


if(isset($_POST['save'])){
    $uidIn = !empty($_POST['uid']) ? trim($_POST['uid']) : null;
    $pidIn = !empty($_POST['pid']) ? trim($_POST['pid']) : null;

    $uid = htmlspecialchars($uidIn, ENT_QUOTES, 'UTF-8');
    $pid = htmlspecialchars($pidIn, ENT_QUOTES, 'UTF-8');

    //Preparing insert statement
    $sql = "INSERT INTO saved (uid, pid) VALUES (:uid, :pid)";
    $stmt = $pdo->prepare($sql);
    //Bind varibles
    $stmt->bindValue(':uid', $uid);
    $stmt->bindValue(':pid', $pid);


    //Execute the statement
    $result = $stmt->execute();

    //If successful, returns to user profile
    if($result) {
        header('Location: ' . $url . 'palette/' . $pid);
    }
}

if(isset($_POST['unsave'])){
    $uidIn = !empty($_POST['uid']) ? trim($_POST['uid']) : null;
    $pidIn = !empty($_POST['pid']) ? trim($_POST['pid']) : null;

    $uid = htmlspecialchars($uidIn, ENT_QUOTES, 'UTF-8');
    $pid = htmlspecialchars($pidIn, ENT_QUOTES, 'UTF-8');

    //Preparing insert statement
    $delete = "DELETE FROM saved WHERE uid = :uid AND pid = :pid";
    $stmt = $pdo->prepare($delete);
    //Bind varibles
    $stmt->bindValue(':uid', $uid);
    $stmt->bindValue(':pid', $pid);


    //Execute the statement
    $result = $stmt->execute();

    //If successful, returns to user profile
    if($result) {
        header('Location: ' . $url . 'palette/' . $pid);
    }
}




/*
  Accept email of user whose password is to be reset
  Send email to user to reset their password
*/
if (isset($_POST['reset-password'])) {
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    // ensure that the user exists on our system
    $query = "SELECT email FROM user WHERE email='$email'";
    $results = $pdo->prepare($query);
    $results->execute();
    //Fetch the row
    $row = $results->fetch(PDO::FETCH_ASSOC);
  
    if (empty($email)) {
      array_push($errors, "Your email is required");
    }else if($row['num'] > 0) {
      array_push($errors, "Sorry, no user exists on our system with that email");
    }
    // generate a unique random token of length 100
    $token = bin2hex(random_bytes(50));
  
    if (count($errors) == 0) {
      // store token in the password-reset database table against the user's email
      $sql = "INSERT INTO password_reset(email, token) VALUES ('$email', '$token')";
      $insert = $pdo->prepare($sql);
      $result = $insert->execute();
  
      // Send email to user with the token in a link they can click on
        $to = $email;
        $subject = "Reset your password on Block Palettes";
        $msg = "Hi there, click on this <a href=" . $url . "new_password?token=" . $token . ">link</a> to reset your password on our site.";
        $msg = wordwrap($msg,70);
        $headers .= "Organization: Block Palettes\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $headers .= "Reply-To: Block Palettes <hello@blockpalettes>\r\n";
        $headers .= "Return-Path: Block Palettes <hello@blockpalettes>\r\n";
        $headers .= "From: Block Palettes <hello@blockpalettes.com>\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
      mail($to, $subject, $msg, $headers);
      $_SESSION['email'] = $email;
      $_SESSION['token'] = $token;
      header('location: ' . $url . 'pending?email=' . $email);
    }
  }
  
  // ENTER A NEW PASSWORD
  if (isset($_POST['new_password'])) {
    $new_pass = !empty($_POST['new_pass']) ? trim($_POST['new_pass']) : null;
    $new_pass_c = !empty($_POST['new_pass_c']) ? trim($_POST['new_pass_c']) : null;
  
    // Grab to token that came from the email link
    $token = $_POST['token'];
    if ($new_pass !== $new_pass_c){
        array_push($errors, "Password do not match");
        $_SESSION['error'] = "error";
        header('Location: ' . $url . 'new_password?token=' . $token);
        exit();
    } else {
      // select email address of user from the password_reset table 
      $sql = "SELECT email FROM password_reset WHERE token='$token' LIMIT 1";
      $results = $pdo->prepare($sql);
      $results->execute();
    //Fetch the row
      $emailCheck = $results->fetch(PDO::FETCH_ASSOC);
      
        $email = $emailCheck['email'];

      if ($email) {
        $new_pass = password_hash($new_pass, PASSWORD_BCRYPT, array('cost' => 12));
        $sql = "UPDATE user SET password='$new_pass' WHERE email='$email'";
        $results = $pdo->prepare($sql);
        $results->execute();
        header('location: ' . $url . '');
      }
    }
  }

if (isset($_POST['updateprofile'])) {
    $bioIn = !empty($_POST['bio']) ? trim($_POST['bio']) : null;
    $bio = htmlspecialchars($bioIn, ENT_QUOTES, 'UTF-8');

    $username = $_POST['username'];
    $uid = $_POST['uid'];

    //Checking if username already exists
    //Preparing SQL statement
    $sql = "SELECT COUNT(id) AS num FROM user_profile WHERE uid = $uid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    //Fetch the row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //Username already exists error
    if($row['num'] > 0){
        $bioUpdate = "UPDATE user_profile SET bio = '$bio' WHERE uid = '$uid'";
        $update = $pdo->prepare($bioUpdate);
        $result = $update->execute();
        if($result) {
            header('Location: ' . $url . 'profile/' . $username);
        }
    } else {
        $sql = "INSERT INTO user_profile (uid, bio) VALUES (:uid, :bio)";
        $stmt = $pdo->prepare($sql);
        //Bind varibles
        $stmt->bindValue(':uid', $uid);
        $stmt->bindValue(':bio', $bio);
    
        //Execute the statement
        $result = $stmt->execute();
    
        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'profile/' . $username);
        }
    }
}
    

?>
