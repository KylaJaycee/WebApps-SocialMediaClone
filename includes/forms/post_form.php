<?php
    //Posting functions
    // include("includes/classes/User.php");
    // include("includes/classes/Post.php");

    if(isset($_POST["post_submit"])){
        //validation to see if post content is in preferred format
        $check_post = 1; 
        $post_img_name = $_FILES["post_img"]["name"];
        $errormsg = "";

        if($post_img_name != ""){
            //create unique name to avoid override files with similar name
            $dir = "assets/images/posts/";
            $post_img_name = $dir . uniqid() . basename($post_img_name);
            $img_file_type = pathinfo($post_img_name, PATHINFO_EXTENSION);

            if($_FILES["post_img"]["size"] > 10000000 ){
                $errormsg = "Image file too big.";
                $check_post = 0;
            }

            if (strtolower($img_file_type) != "jpeg" && strtolower($img_file_type) != "jpg" && strtolower($img_file_type) != "png"){
                $errormsg = "File type not allowed. Only upload jpeg, jpg and png. " . $img_file_type;
                $check_post = 0;
            }

            if($check_post){
                if(move_uploaded_file($_FILES["post_img"]["tmp_name"], $post_img_name)){
                    //post okay to upload
                    
                }else{
                    //post not uploadable
                }
            }
        }

        if($check_post){
            $post = new Post($connect, $active_user);
            $post->submitPost($_POST["post_caption"], $post_img_name, $_POST["post_tag"]);
        }else{
            echo "<div class='alert alert-danger'>
             $errormsg;
            </div>";
        }

    }else{
        echo "post unsuccessful";
    }
?>
