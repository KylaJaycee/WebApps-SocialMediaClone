<?php
    ob_start();

    $timezone = date_default_timezone_set("Asia/Dubai");

    //store variables in a session to avoid deletion if error
    session_start();

    //connect to
    $connect = mysqli_connect("localhost","root","","socmed");

    //if error when connecting
    if(mysqli_connect_errno()){
        echo "Unable to connect to database: " . mysqli_connect_errno(); 
    }

?>