<?php 


// Functions
$url = "https://localhost/blockpalettes/";

function time_elapsed_string($datetime, $full = false) {
    date_default_timezone_set("America/New_York");
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

if(isset($_POST['like'])){
    $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

    $current_url = $_POST['current_url'];


    $palettePull = $pdo->prepare("SELECT likes FROM palette WHERE id = $id");
    $palettePull->execute();
    $palette = $palettePull->fetch(PDO::FETCH_ASSOC);

    $likes = $palette['likes'];

    $likes++;

    $sql = "UPDATE palette SET likes='$likes'  WHERE id='$id'";
            if ($pdo->query($sql)) {
                setcookie('likes', $_COOKIE['likes'] . "," . $id, time() + strtotime('+20 years'), '/' );
                header('Location: ' . $url . 'popular');
                exit;
            } else {
                exit;
            }
            $pdo = null;

}

if(isset($_POST['unlike'])){
    $id = !empty($_POST['id']) ? trim($_POST['id']) : null;

    $palettePull = $pdo->prepare("SELECT likes FROM palette WHERE id = $id");
    $palettePull->execute();
    $palette = $palettePull->fetch(PDO::FETCH_ASSOC);

    $likes = $palette['likes'];

    $likes--;

    $sql = "UPDATE palette SET likes='$likes'  WHERE id='$id'";
            if ($pdo->query($sql)) {
                $cookie = $_COOKIE['likes'];
                $cookieMinus = str_replace("," . $id, "", $cookie);
                setcookie('likes', $cookieMinus, time() + strtotime('+20 years'), '/' );
                header('Location: ' . $url . 'popular');
                exit;
            } else {
                exit;
            }
            $pdo = null;

}

//New Job
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


?>