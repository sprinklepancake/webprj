<?php
//hasan
session_start();

//clear all session variables
$_SESSION = array();

//destroy session
session_destroy();

//redirect to login page 
header('Location: ../hasan/login.php');
exit;