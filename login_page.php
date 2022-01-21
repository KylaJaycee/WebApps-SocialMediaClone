<?php
    require "config/config.php";
    require "includes/forms/login_form.php";
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
    <title>Login</title>
</head>
<body>
    <div class="row">
        <div class="col px-5 mx-0 my-auto">
            <div class="row text-center">
                <h2>Login</h2>
            </div>
            <div class="row">
                <div class="card brd-outline">
                    <div class="card-body col">
                        <form class="row" action="login_page.php" method="POST">
                            <?php 
                                if(in_array("Email or Password in incorrect",$error_msg)){
                                    echo "<div class='alert alert-danger'>Email or Password in incorrect.</div>";
                                }
                            ?>
                            <div class="mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input  type="email" name="email" class="form-control" id="email" value="<?php
                                    if(isset($_SESSION["email"])){
                                        echo $_SESSION["email"];
                                    }
                                ?>"required>
                            </div>
                            
                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            
                            <div>
                                <input class="btn btn-primary bg-pink btn-outline-dark w-100" type="submit" name="login_button" value="Log In" >
                            </div>
                                
                        </form> 
                        <div class="row text-center mt-3">
                            <a href="signup_page.php">Don't have an account yet? Sign Up here.</a>
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