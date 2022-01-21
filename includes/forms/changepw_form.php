<?php
    $error_msg=array();

    if(isset($_POST["changepw_submit"])){
        //password
        $password = strip_tags($_POST["changepw"]); //remove html tags
        $password2 = strip_tags($_POST["changepw2"]); //remove html tags

        //check password length
        if(strlen($password) > 35 || strlen($password) < 8) {
            array_push($error_msg, "Your password must be between 8 and 35 characters");
        }

        //compare password to match
        if($password !== $password2){
            array_push($error_msg, "Passwords don't match");
        }

        if(empty($error_msg)){ 
            //encrypt pass
            $password = md5($password);
            mysqli_query($connect, "UPDATE users SET password='$password' WHERE username='$active_user'");
        }
    }
?>