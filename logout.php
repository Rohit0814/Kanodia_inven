<?php
session_start();
$shop=$_SESSION['shop_name'];
session_destroy();
if (isset($_COOKIE['userdata'])) {
    unset($_COOKIE['userdata']);
    setcookie('userdata', null, -1, '/');
} 
header("Location:backup/backup.php?time=logout&shop=$shop");
//header("Location:index.php");
?>