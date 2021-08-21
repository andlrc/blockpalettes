<?php
//connect.php
//MYSQL DB Details
define('MYSQL_USER', 'nate');
define('MYSQL_PASSWORD', '');
define('MYSQL_HOST', 'localhost:8889');
define('MYSQL_DATABASE', 'blockpalettes');

//PDO Options
$pdoOptions = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false) ;

//Make Connection to MYSQL
try {
    $pdo = new PDO(
        "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE,
        MYSQL_USER,
        MYSQL_PASSWORD,
        $pdoOptions
    );
}
catch (PDOException $e) { print $e->getMessage();

}

?>