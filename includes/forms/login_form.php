<?php
    $error_msg = array();
    if(isset($_POST["login_button"])){
        //remove illegal chars
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

        //create session to save email
        $_SESSION["email"] = $email;

        //encrypt password to match data in db
        $password = md5($_POST["password"]);

        //find row with similar data
        $check_db = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' AND password='$password'");
        $check_login = mysqli_num_rows($check_db);

        if($check_login == 1){
            $data_row = mysqli_fetch_array($check_db);
            $username = $data_row["username"];
            $_SESSION["username"] = $username;
            header("Location: index.php");
            exit();
        }else{
            array_push($error_msg, "Email or Password in incorrect");
            // echo "<div class='alert alert-danger'>Email or Password in incorrect.</div>";
        }
    }
?>