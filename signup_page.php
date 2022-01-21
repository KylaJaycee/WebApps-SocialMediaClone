<?php
    require "config/config.php";
    require "includes/forms/signup_form.php";
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/stylesheet.css">
    <style>
        body{
            display: grid;
            overflow-y: auto;
            overflow-x: hidden;
            width: 100vw;
            height: 100vh;
        }
        h1{
            color: white;
        }
    </style>
    <title>Sign Up</title>
</head>
<body>
    <div class="row">
        <div class="col px-5 mx-0 my-auto">
            <div class="row text-center">
                <h2>Signup</h2>
            </div>
            <div class="row">
                <div class="card brd-outline">
                    <div class="card-body col">
                        <form class="row" action="signup_page.php" method="POST">
                            <div class="mb-2">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" id="username" value="<?php 
                                if(isset($_SESSION["username"])){
                                    echo $_SESSION["username"];
                                }?>"required>
                                <!-- if in use, display error -->
                                <?php 
                                    if(in_array("Username already in use",$error_msg)){
                                        echo "<div class='alert alert-danger'>Username already in use</div>";
                                    }
                                ?>
                            </div>
                            
                            <div class="mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="<?php
                                    if(isset($_SESSION["email"])){
                                        echo $_SESSION["email"];
                                    }
                                ?>"required>
                                <!-- if in use, display error -->
                                <?php 
                                    if(in_array("Email already in use",$error_msg)){
                                        echo "<div class='alert alert-danger'>Email already in use</div>";
                                    }elseif(in_array("Invalid Format",$error_msg)){
                                        echo "<div class='alert alert-danger'>Invalid Format</div>";
                                    }
                                ?>
                            </div>

                            <div class="mb-2">
                                <div class="mb-2">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" required>
                                </div>
                                <label for="password" class="form-label">Confirm Password</label>
                                <input type="password" name="password2" class="form-control" id="c-pass" required>
                                <!-- if incorrect, display error -->
                                <?php 
                                    if(in_array("Your password must be between 8 and 35 characters",$error_msg)){
                                        echo "<div class='alert alert-danger'>Your password must be between 8 and 35 characters</div>";
                                    }elseif(in_array("Passwords don't match",$error_msg)){
                                        echo "<div class='alert alert-danger'>Passwords don't match</div>";
                                    }
                                ?>
                                <br>
                            </div>
                            
                            <div>
                                <input class="btn btn-primary bg-pink btn-outline-dark w-100"  type="submit" name="signup_button" value="Create Account">
                            </div>
                        </form>
                        <div class="row text-center">
                            <a href="login_page.php">Already have an account? Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
        <div class="col bg-darkgreen px-5" style="display: grid; align-content: center;">
            <div class="row w-100">
                <img src="assets/images/plant.png" class="img-fluid img-pfp w-50 my-0 mx-auto" alt="">
                <h1 class="text-center mt-3">Horticulture</h1>
            </div>
        </div>
    </div>
</body>
</html>