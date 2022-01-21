<?php
    $error_msg=array();
    if(isset($_POST["changepfp_submit"])){
        $check_changepfp = 1;
        $changepfp_img_name = $_FILES["changepfp_img"]["name"];
        if($changepfp_img_name != ""){
            //create unique name to avoid override files with similar name
            $dir = "assets/images/pfps/";
            $changepfp_img_name = $dir . uniqid() . basename($changepfp_img_name);
            $img_file_type = pathinfo($changepfp_img_name, PATHINFO_EXTENSION);

            if($_FILES["changepfp_img"]["size"] > 10000000 ){
                $errormsg = "Image file too big.";
                $check_changepfp = 0;
            }

            if (strtolower($img_file_type) != "jpeg" && strtolower($img_file_type) != "jpg" && strtolower($img_file_type) != "png"){
                $errormsg = "File type not allowed. Only upload jpeg, jpg and png. " . $img_file_type;
                $check_changepfp = 0;
            }

            if($check_changepfp){

                if(move_uploaded_file($_FILES["changepfp_img"]["tmp_name"], $changepfp_img_name)){
                    
                    mysqli_query($connect, "UPDATE users SET profile_pic = '$changepfp_img_name' WHERE username = '$active_user'");
                }else{
                    //pfp not uploadable
                }
            }
        }
    }
?>