<?php 

    if(!empty($_GET['pagename']) && $_GET['pagename']=="cutting-sheet"){
        include "../cutting/index.php";
    }

    // elseif(!empty($_GET['pagename']) && $_GET['pagename']=="stitching-recoard"){
    //     include "../stitching/index.php";
    // }

    // elseif(!empty($_GET['pagename']) && $_GET['pagename']=="zigzag-recoard"){
    //     include "../zigzag/index.php";
    // }
    else{
        include "./other.php";
    }
   
?>