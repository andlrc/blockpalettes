<?php 

require "password.php";
if(isset($_POST['login'])){

    //Retrieve the field values from our login form.
    $emailInput = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $passwordAttemptInput = !empty($_POST['password']) ? trim($_POST['password']) : null;

    $email = htmlspecialchars($emailInput, ENT_QUOTES, 'UTF-8');
    $passwordAttempt = htmlspecialchars($passwordAttemptInput, ENT_QUOTES, 'UTF-8');

    //Retrieve the user account information for the given username.
    $sql = "SELECT id, email, password FROM user WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':email', $email);

    //Execute.
    $stmt->execute();

    //Fetch row.
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //If $row is FALSE.
    if($user === false){
        //Could not find a user with that username!
        //PS: You might want to handle this error in a more user-friendly manner!
        $error = "User doesnt exists!";
    } else{
        //User account found. Check to see if the given password matches the
        //password hash that we stored in our users table.

        //Compare the passwords.
        $validPassword = password_verify($passwordAttempt, addslashes(htmlspecialchars($user['password'])));

        //If $validPassword is TRUE, the login has been successful.
        if($validPassword){

            //Provide the user with a login session.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['logged_in'] = time();

            //Redirect to our protected page, which we called home.php
            header('Location: ../dashboard');
            exit;

        } else{
            //$validPassword was FALSE. Passwords do not match.
            $error = "Passwords do not match!";
        }
    }

}

// Functions
$url = "https://www.blockpalettes.com/";

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
        
            //Preparing insert statement
            $sql = "INSERT INTO palette (blockOne, blockTwo, blockThree, blockFour, blockFive, blockSix) VALUES (:blockOne, :blockTwo, :blockThree, :blockFour, :blockFive, :blockSix)";
            $stmt = $pdo->prepare($sql);
            //Bind varibles
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
                $_SESSION['create'] = "New Palette";
                header('Location: ' . $url . 'new');
            }
        
         }
    } else {
        header('Location: ' . $url . 'new');
    }

}
//New Job
if(isset($_POST['unfavorite'])){
    $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

    //Updates table
    $edit = "UPDATE palette SET featured = 0 WHERE id ='$id'";
    $stmt = $pdo->prepare($edit);

    $result = $stmt->execute();

    //If successful, returns to user profile
    if($result) {
        header('Location: ' . $url . 'dashboard');
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
        header('Location: ' . $url . 'dashboard');
    }  
}

?>