<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="assets/js/bootstrap.js"></script>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/stylesheet.css">
    <style>
        body{
            overflow-x: hidden;
            width: 100%;
        }
    </style>
    <title>View Comments</title>
</head>
<body>
<?php
    require "config/config.php";
    include("includes/classes/User.php");
    include("includes/classes/Post.php");
    if(isset($_SESSION["username"])){
        $active_user = $_SESSION["username"];
        $user_query = mysqli_query($connect, "SELECT * FROM users WHERE username='$active_user'");
        $user_details = mysqli_fetch_array($user_query);
    }else{
        //else load login page
        header("Location: login_page.php");
    }

    if(isset($_GET["post_id"])){
        $post_id = $_GET["post_id"];
    }

    $post_query = mysqli_query($connect, "SELECT post_by FROM posts WHERE id='$post_id'");
    $row = mysqli_fetch_array($post_query);
    $posted_to = $row["post_by"];

    if(isset($_POST['comments' . $post_id])){
        $comment_text = $_POST["comment_text"];
        $comment_text = mysqli_escape_string($connect,$comment_text);
        $current_date_time = date("Y-m-d- H:i:s");
        $insert_comment = mysqli_query($connect, "INSERT INTO comments VALUES ('', '$comment_text', '$active_user', '$posted_to', '$current_date_time', 'no', '$post_id')");
        // echo "<div class='alert alert-success  alert-dismissible fade show' id='comment_alert'>
        //     Comment posted
        // </div>";
    }else{
        // echo "<div class='alert alert-danger  alert-dismissible fade show' id='comment_alert'>
        //     Comment failed
        // </div>";
    }

?>

    <form action="comments.php?post_id=<?php echo $post_id;?>" name="comments<?php echo $post_id;?>" method="POST">       
        <textarea name="comment_text" id="" class="commentfield form-control" cols="30" rows="3" required></textarea>
        <input type="submit" class= "commentbtn btn btn-secondary" name="comments<?php echo $post_id;?>" value="Comment" data-id="<?php echo $post_id?>">
    </form>
    <hr>

    <div class="col gap-3" style="display: grid;">
        

<?php
    $get_comments = mysqli_query($connect, "SELECT * FROM comments WHERE post_id = '$post_id' ORDER BY id DESC");
    $count = mysqli_num_rows($get_comments);

    if($count != 0){
        while($comment = mysqli_fetch_array($get_comments)){
            $comment_body = $comment["comment_text"];
            $comment_to = $comment["comment_to"];
            $comment_by = $comment["comment_by"];
            $comment_date = $comment["comment_date"];
            $deleted = $comment["deleted"];

            $current_date_time = date("Y-m-d H:i:s");
            $start_date = new DateTime($comment_date); //post date 
            $end_date= new DateTime($current_date_time); //current date
            $timespan = $start_date->diff($end_date); //diff between dates
            if($timespan->y == 1){
                if($timespan == 1){
                    $timespan_msg = $timespan->y . " year ago";
                }else{
                    $timespan_msg = $timespan->y . " years ago";
                }
            }else if($timespan -> m == 1){
                if($timespan-> d == 0){
                    $days = " ago";
                }else if($timespan-> d == 1){
                    $days = $timespan-> d . " day ago";
                }else{
                    $days = $timespan-> d . " days ago";
                }

                if($timespan->m == 1){
                    $timespan_msg = $timespan->m . " month" . $days;
                }else{
                    $timespan_msg = $timespan->m . " months" . $days;
                }
            }else if($timespan->d == 1){
                if($timespan-> d == 1){
                    $timespan_msg  = "Yesterday";
                }else{
                    $timespan_msg  = $timespan-> d . " days ago";
                }
            }else if($timespan->h >= 1){
                if($timespan->h == 1){
                    $timespan_msg  = $timespan->h . " hour ago";
                }else{
                    $timespan_msg  = $timespan->h . " hours ago";
                }
            }else if($timespan->i >= 1){
                if($timespan->i == 1){
                    $timespan_msg  = $timespan->i . " minute ago";
                }else{
                    $timespan_msg  = $timespan->i . " minutes ago";
                }
            }else{
                if($timespan->s < 30){
                    $timespan_msg  = "Just now";
                }else{
                    $timespan_msg  = $timespan->s . " seconds ago";
                }
            }


            $user_obj = new User($connect,$comment_by);

            ?>
            <div class="row">
                <div class="card" style="width: 100vw;">
                    <div class="col card-body">
                        <div class="row card-text">

                            <div class="col-2">
                                <a href="<?php echo $comment_by;?>" target="_parent"><img src="<?php echo $user_obj->getpfp();?>" class='img-fluid img-pfp' alt=""></a>
                            </div>

                            <div class="col-5 card-details">
                                <div class="row"><a class="post-by" href="<?php echo $comment_by;?>" target="_parent"><?php echo $comment_by?></a></div>
                                <div class="row"><p class="post-time"><?php echo $timespan_msg?></p></div>
                            </div>

                            <!-- <div class="col-5 card-tag"><a href="" class="post-tag"><i class="bi bi-three-dots-vertical"></i>A</a></div> -->
                        </div>

                        <div class="row post-caption card-text">
                            <p class="col"><?php echo $comment_body?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }else{
        echo "No Comments Yet";
    }
?>


    </div>


    <!-- <script>
         $(document).ready(function(){
            $('.commentbtn').on('click',function(){
                console.log('comment post clicked');

                var post_id = $(this).data('id');
                $submit_btn = $(this);
                $comment_field = $submit_btn.siblings('textarea.commentfield');

                //get iframe parent html 
                $commentbtn_id = "span.comment-count-" +post_id;
                $parent = $("#comment_iframe", window.parent.document.body);
                $comment_count = $parent.find($commentbtn_id);
                // $comment_count = $("#comment_iframe", window.parent.document.body).find($commentbtn_id);
                // count = parseInt($comment_count.text());

                if($comment_field.val()){ //not empty
                    console.log('comment field not empty');
                    // count = count + 1; 
                    // $comment_count.text(count);
                    console.log('id ' + $commentbtn_id + 'comment count ' + $comment_count.text());
                }else{
                    console.log('comment field empty');
                }
            });
        });
    </script> -->
</body>
</html>