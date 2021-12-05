<?php 

require "password.php";
require "connect.php";

if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
    $uid = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = '$uid'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $userid = $user['id'];
    $username = $user['username'];
  }

$url = "https://www.blockpalettes.com/";

if(isset($_REQUEST["term"])) {
// Prepare a select statement
    $param_term = $_REQUEST["term"] . '%';
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username LIKE :search");
    $stmt->bindValue(':search', $param_term, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result) {
        if ($result != false) {
            foreach ($result as $row) {
                $rankid = $row['rank'];
                $rankPull = $pdo->prepare("SELECT * FROM rank WHERE id = '$rankid'");
                $rankPull ->execute();
                $rank = $rankPull ->fetch(PDO::FETCH_ASSOC);
                echo '<div class="col-xl-12">
                       <a href="'.$url.'dashboard/user/'. $row['id']. '" class="nav-link">
                         <div class="user-mgmt-float" style="margin-top:10px!important">
                            <div class="role-pill" style="background:'.$rank['rank_color'].'">'. ucwords(mb_substr($rank['rank_name'], 0, 1, "UTF-8")) . '</div>
                               '.ucwords($row['username']).'
                            </div>
                       </a>
                     </div>
                     ';
            }
        } else {
            echo "<p>No matches found</p>";
        }
    }
}

//Register a user
if(isset($_POST['register'])){
    $error = "";
    //Retrieves input data
    $username = htmlspecialchars(addslashes($_POST['username']));
    $email = htmlspecialchars(addslashes($_POST['email']));
    $password = htmlspecialchars(addslashes($_POST['password']));


    if (ctype_alnum($username)){

    } else {
        $error = 'Username must only contain alphanumeric characters';
        $_SESSION['invalidUsername'] = "error";
        header('Location: ' . $url . '');
        exit;
    }


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

     //create verify path
     $query = "SELECT email FROM user WHERE email='$email'";
     $results = $pdo->prepare($query);
     $results->execute();
     //Fetch the row
     $row = $results->fetch(PDO::FETCH_ASSOC);
   
     // generate a unique random token of length 100
     $token = bin2hex(random_bytes(50));
 
     // store token in the password-reset database table against the user's email
     $sql = "INSERT INTO email_verify(email, token) VALUES ('$email', '$token')";
     $insert = $pdo->prepare($sql);
     $result = $insert->execute();
   
     if($result) {
 
           // Send email to user with the token in a link they can click on
         $to = $email;
         $subject = "Welcome to Block Palettes! Verify Your Account";
         $msg = "Hi there, <br>Thank you for creating an account on our website!<br>Click on this <a href=" . $url . "verify?token=" . $token . ">link</a> to verify your account.<br> Create your own block palettes or browse hundreds of community made palettes!<br>- Block Palettes Staff";
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
 
         $_SESSION['userRegister'] = "success";
         $_SESSION['email'] = $email;
         $_SESSION['token'] = $token;
         header('Location: ' . $url . '');
         exit;
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
        $_SESSION['badEmail'] = "bad email";
        header('Location: ' . $url . '');
        exit;
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
            $_SESSION['badPassword'] = "bad password";
            header('Location: ' . $url . '');
            exit;
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


if($user['verified'] == 1) {
    date_default_timezone_set("America/Los_Angeles");
    $duedate = gmdate('Y-m-d h:i:s', strtotime('- 60 seconds'));

    $post = $pdo->prepare("SELECT id, MAX(date) as lastpost FROM palette WHERE uid = '$uid'");
    $post->execute();
    $lastpost = $post->fetch(PDO::FETCH_ASSOC);

    $last = $lastpost['lastpost'];

    if ($duedate >= $last){
    /*  u can post */
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

        $uid = $userid;

        // Form validation to check if blocks are in img folder
        $dir =  "img/block/*.png";
        $images = glob( $dir );
        if(in_array($blockOne, $images) == false) {
            $_SESSION['blockError'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if(in_array($blockTwo, $images) == false) {
            $_SESSION['blockError'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if(in_array($blockThree, $images) == false) {
            $_SESSION['blockError'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if(in_array($blockFour, $images) == false) {
            $_SESSION['blockError'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if(in_array($blockFive, $images) == false) {
            $_SESSION['blockError'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if(in_array($blockSix, $images) == false) {
            $_SESSION['blockError'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        }

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

        // checks to see if there are duplicate blocks
        $check = array($oneCleanStr, $twoCleanStr, $threeCleanStr, $fourCleanStr, $fiveCleanStr, $sixCleanStr);
        $tmp = array_count_values($check);
        $cnt = $tmp[$oneCleanStr];
        $cnt2 = $tmp[$twoCleanStr];
        $cnt3 = $tmp[$threeCleanStr];
        $cnt4 = $tmp[$fourCleanStr];
        $cnt5 = $tmp[$fiveCleanStr];
        $cnt6 = $tmp[$sixCleanStr];

        if ($cnt !== 1){
            $_SESSION['blockDup'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if ($cnt2 !== 1) {
            $_SESSION['blockDup'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if ($cnt3 !== 1) {
            $_SESSION['blockDup'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if ($cnt4 !== 1) {
            $_SESSION['blockDup'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if ($cnt5 !== 1) {
            $_SESSION['blockDup'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        } else if ($cnt6 !== 1) {
            $_SESSION['blockDup'] = "error";
            header('Location: ' . $url . 'submit');
            exit();
        }


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
            if ($row['num'] > 0) {
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
            if ($result) {
                $_SESSION['last_submit'] = time();
                $_SESSION['create'] = "New Palette";
                header('Location: ' . $url . 'palettes');
            }
        }
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



if(isset($_POST['liked'])){
    $pidIn = !empty($_POST['postid']) ? trim($_POST['postid']) : null;
    $pid = htmlspecialchars($pidIn, ENT_QUOTES, 'UTF-8');
    $uid = $user['id'];

    $savePull = $pdo->prepare("SELECT * FROM palette WHERE id = $pid");
    $savePull->execute();
    $save = $savePull->fetch(PDO::FETCH_ASSOC);

    $n = $save['likes'];
    
    $sql = "SELECT uid FROM saved WHERE uid='$uid' AND pid='$pid'";
    $stmt = $pdo->prepare($sql);

    //Execute the statement
    $stmt->execute();
    //Fetch the row
    $check = $stmt->fetch(PDO::FETCH_ASSOC);

    if($check){
        return;
    }

    //Preparing insert statement
    $sql = "INSERT INTO saved (uid, pid) VALUES (:uid, :pid)";
    $stmt = $pdo->prepare($sql);
    //Bind varibles
    $stmt->bindValue(':uid', $uid);
    $stmt->bindValue(':pid', $pid);
    //Execute the statement
    $result = $stmt->execute();

    $edit = "UPDATE palette SET likes = $n+1 WHERE id ='$pid'";
    $stmt = $pdo->prepare($edit);
    $result = $stmt->execute();

    //If successful, returns to user profile
    if($result) {
        echo $n+1;
        exit();
    }
}

if(isset($_POST['unliked'])){
    $pidIn = !empty($_POST['postid']) ? trim($_POST['postid']) : null;
    $pid = htmlspecialchars($pidIn, ENT_QUOTES, 'UTF-8');
    $uid = $user['id'];


    $savePull = $pdo->prepare("SELECT * FROM palette WHERE id = $pid");
    $savePull->execute();
    $save = $savePull->fetch(PDO::FETCH_ASSOC);

    $n = $save['likes'];

    $sql = "SELECT uid FROM saved WHERE uid='$uid' AND pid='$pid'";
    $stmt = $pdo->prepare($sql);

    //Execute the statement
    $stmt->execute();
    //Fetch the row
    $check = $stmt->fetch(PDO::FETCH_ASSOC);

    if($check){
       

    //Preparing insert statement
    $sql = "DELETE FROM saved WHERE pid = $pid AND uid = $uid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $edit = "UPDATE palette SET likes =$n-1 WHERE id =$pid";
    $stmt = $pdo->prepare($edit);
    $stmt->execute();

    //If successful, returns to user profile
    
        echo $n-1;
        exit();

    } else {
        return;
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

        $ignIn = !empty($_POST['ign']) ? trim($_POST['ign']) : null;
        $ign = htmlspecialchars($ignIn, ENT_QUOTES, 'UTF-8');
    

        $userid = $_POST['uid'];
        $username = $_POST['username'];
           

        //Checking if username already exists
        //Preparing SQL statement
        $sql = "SELECT COUNT(id) AS num FROM user_profile WHERE uid = $userid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        //Fetch the row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['num'] > 0){
            if (isset($_POST['bio'])) {
                $bioUpdate = "UPDATE user_profile SET bio = '$bio' WHERE uid = '$userid'";
                $update = $pdo->prepare($bioUpdate);
                $resultbio = $update->execute();
            } else {
                header('Location: ' . $url . 'profile/' . $username);
            }
            if (isset($_POST['ign'])) {
                $ignUpdate = "UPDATE user_profile SET minecraft_ign = '$ign' WHERE uid = '$userid'";
                $updateign = $pdo->prepare($ignUpdate);
                $result = $updateign->execute();
            } else {
                header('Location: ' . $url . 'profile/' . $username);
            }

            if ($resultbio || $result) {
                header('Location: ' . $url . 'profile/' . $username);
            }
        } else {
            if ($_POST['bio'] !== "") {
                $sql = "INSERT INTO user_profile (uid, bio) VALUES (:uid, :bio)";
                $stmt = $pdo->prepare($sql);
                //Bind varibles
                $stmt->bindValue(':uid', $userid);
                $stmt->bindValue(':bio', $bio);

                //Execute the statement
                $resultbio = $stmt->execute();
            } else {
                header('Location: ' . $url . 'profile/' . $username);
            }

            $sql = "SELECT COUNT(id) AS num FROM user_profile WHERE uid = $userid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            //Fetch the row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row['num'] > 0) {
                if (isset($_POST['ign'])) {
                    $ignUpdate = "UPDATE user_profile SET minecraft_ign = '$ign' WHERE uid = '$userid'";
                    $updateign = $pdo->prepare($ignUpdate);
                    $result = $updateign->execute();
                } else {
                    header('Location: ' . $url . 'profile/' . $username);
                }
            } else {
                if ($_POST['ign'] !== "") {
                    $insertIgn = "INSERT INTO user_profile (uid, minecraft_ign) VALUES (:uid, :ign)";
                    $stmtign = $pdo->prepare($insertIgn);
                    //Bind varibles
                    $stmtign->bindValue(':uid', $userid);
                    $stmtign->bindValue(':ign', $ign);

                    //Execute the statement
                    $result = $stmtign->execute();
                } else {
                    header('Location: ' . $url . 'profile/' . $username);
                }
            }

            //If successful, returns to user profile
            if($resultbio || $result) {
                header('Location: ' . $url . 'profile/' . $username);
            }
        }
    }


if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
if($user['rank'] > 90){
    //User updates


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


    //hide
    if(isset($_POST['unhide'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

        //Updates table
        $edit = "UPDATE palette SET hidden = 0 WHERE id ='$id'";
        $stmt = $pdo->prepare($edit);

        $result = $stmt->execute();

        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/palettes');
        }
    }

    if(isset($_POST['hide'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

        //Updates table
        $edit = "UPDATE palette SET hidden = 1 WHERE id ='$id'";
        $stmt = $pdo->prepare($edit);

        $result = $stmt->execute();

        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/palettes');
        }
    }


    if(isset($_POST['updateRank'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;
        $rank = !empty($_POST['rank']) ? trim($_POST['rank']) : null;

        //Updates table
        $edit = "UPDATE user SET rank = '$rank' WHERE id ='$id'";
        $stmt = $pdo->prepare($edit);

        $result = $stmt->execute();

        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/user/' . $id . '');
        }
    }

    if(isset($_POST['giveAward'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;
        $award = !empty($_POST['award']) ? trim($_POST['award']) : null;
        $award_name = !empty($_POST['award_name']) ? trim($_POST['award_name']) : null;
        $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
        $username = !empty($_POST['username']) ? trim($_POST['username']) : null;

        //Checking if username already exists
        //Preparing SQL statement
        $sql = "SELECT COUNT(id) AS num FROM user_awards WHERE uid = $id AND award_id = $award";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        //Fetch the row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //Username already exists error
        if($row['num'] > 0){
            $delete = "DELETE FROM user_awards WHERE uid = :uid AND award_id = :award_id";
            $stmt = $pdo->prepare($delete);
            //Bind varibles
            $stmt->bindValue(':uid', $id);
            $stmt->bindValue(':award_id', $award);

            //Execute the statement
            $result = $stmt->execute();

            //If successful, returns to user profile
            if($result) {
                header('Location: ' . $url . 'dashboard/user/' . $id . '');
            }
        } else {

            //Updates table
            $sql = "INSERT INTO user_awards (uid, award_id) VALUES (:id, :award)";
            $stmt = $pdo->prepare($sql);
            //Bind varibles
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':award', $award);

            $result = $stmt->execute();

            //If successful, returns to user profile
            if ($result) {
                $sql = "SELECT award_name AS name FROM awards WHERE id = $award";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                //Fetch the row
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $award_name = $row['name'];

                // Send email to user with the token in a link they can click on
                $to = $email;
                $subject = "You were given an award on Block Palettes!";
                $msg = "<h4>Congratulations on receiving the award: " . ucwords($award_name) . "<br>
                        Check it out on your profile, <a href='". $url ."profile/". $username ."'>here</a>.</h4>
                    <br>
                        <i>- Block Palettes Staff</i>

                    ";
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


                header('Location: ' . $url . 'dashboard/user/' . $id . '');
            }
        }
    }

    //Mutes user
    if(isset($_POST['shadowBan'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

        //Updates table
        $edit = "UPDATE user SET status = 1 WHERE id ='$id'";
        $stmt = $pdo->prepare($edit);

        $result = $stmt->execute();

        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/user/' . $id . '');
        }
    }

    //Un mutes user
    if(isset($_POST['unshadowBan'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

        //Updates table
        $edit = "UPDATE user SET status = 0 WHERE id ='$id'";
        $stmt = $pdo->prepare($edit);

        $result = $stmt->execute();

        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/user/' . $id . '');
        }
    }

    //Bans user
    if(isset($_POST['ban'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

        //Updates table
        $edit = "UPDATE user SET status = 2 WHERE id ='$id'";
        $stmt = $pdo->prepare($edit);

        $result = $stmt->execute();

        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/user/' . $id . '');
        }
    }

    //Unban user
    if(isset($_POST['unban'])){
        $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

        //Updates table
        $edit = "UPDATE user SET status = 0 WHERE id ='$id'";
        $stmt = $pdo->prepare($edit);

        $result = $stmt->execute();

        //If successful, returns to user profile
        if($result) {
            header('Location: ' . $url . 'dashboard/user/' . $id . '');
        }
    }

}
}

?>
