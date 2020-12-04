<?php
session_start();
require 'logic.php';
//Breaks current session
session_destroy();

//Returns user to login page
header('Location: ' . $url . '');

?>