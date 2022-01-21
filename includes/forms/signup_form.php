<?php    
    //declare var
    $username = "";
    $email = "";
    $password = "";
    $password2 = "";
    $date_made = "";
    $error_msg = array();

    if(isset($_POST['signup_button'])){
        //username
        $username = strip_tags($_POST["username"]); //remove html tags
        $username = str_replace(" ","", $username); //remove space
        $username = strtolower($username); //convert to lowercase
        $_SESSION["username"] = $username; 

        //email
        $email = strip_tags($_POST["email"]); //remove html tags
        $email = str_replace(" ","", $email); //remove space
        $email = strtolower($email); //convert to lowercase
        $_SESSION["email"] = $email; 

        //password
        $password = strip_tags($_POST["password"]); //remove html tags
        $password2 = strip_tags($_POST["password2"]); //remove html tags

        //date
        $date_made = date("Y-m-d");

        //check username availability
        $check_username = mysqli_query($connect, "SELECT username FROM users WHERE username='$username'");

        //count num of rows returned
        $num_rows = mysqli_num_rows($check_username);

        //notify availability
        if($num_rows > 0){
            array_push($error_msg, "Username already in use");
            // echo "Username already in use";
        }

        //check username length
        if(strlen($username) > 12 || strlen($username) < 4) {
            array_push($error_msg, "Your username must be between 4 and 12 characters");
        }

        //validate email format
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            
            //check email availability
            $check_email = mysqli_query($connect, "SELECT email FROM users WHERE email='$email'");

            //count num of rows returned
            $num_rows = mysqli_num_rows($check_email);

            //notify availability
            if($num_rows > 0){
                array_push($error_msg, "Email already in use");
            }

        }else{
            array_push($error_msg, "Invalid Format");
        }

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

            //assign profile pic
            $profile_pic = "assets/images/default_pfp.png";

            //pass data to db
            $query = "INSERT INTO users VALUES ('','$username', '$email', '$password', '$date_made','$profile_pic', '0', '0', 'no friends')";
    
            if(mysqli_query($connect,$query)){
                // echo  "<div class='alert alert-success'>Account Created. Login to access acount.</div>";
                header("Location: index.php");
                exit();
            }else{
                echo "<div class='alert alert-danger'>Data Not Sent. Try Again.</div>" . mysqli_error($connect);
            }

            //clear session
            $_SESSION["username"] = "";
            $_SESSION["email"] = "";

            //load dashboard
        }

    }
?>