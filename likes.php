<script>
    $(document).ready(function(){
        
        $('.likebtn').on('click',function(){

            var post_id = $(this).data('id');
            console.log('likebuttontejdhfkajf: ' + (post_id));

            $like_btn = $(this);
            $like_icon = $like_btn.children('i.bi');
            $like_count = $like_btn.children('span.like-count');
            var count = parseInt($like_count.text());

            console.log('likecount: ' + count);

            if($like_icon.hasClass('bi-heart')){
                action = 'like';
            }else if($like_icon.hasClass('bi-heart-fill')){
                action = 'unlike';
            }

            $.ajax({
                url: 'index.php',
                type: 'post',
                data: {
                    'action': action,
                    'post_id': post_id
                },
                success: function(data){
                    if(action == 'like'){
                        $like_icon.removeClass('bi-heart');
                        $like_icon.addClass('bi-heart-fill');
                        count = count + 1; 
                        $like_count.text(count);
                        console.log('likecount add: ' + count);
                    }else if(action == 'unlike'){
                        $like_icon.removeClass('bi-heart-fill');
                        $like_icon.addClass('bi-heart');
                        count = count - 1; 
                        $like_count.text(count);
                        console.log('likecount deduct: ' + count);
                    }
                }
            })

        });
    });

</script>

<?php
    if (isset($_POST['action'])) {
        $post_id = $_POST['post_id'];
        $action = $_POST['action'];

        // $post_by = mysqli_query($connect,"SELECT post_by FROM posts WHERE id='$post_id'");
        // $num_likes = "SELECT num_likes FROM users WHERE username='$post_by'";
        switch ($action) {
            case 'like':
                $query1="INSERT INTO likes VALUES ('', '$active_user', '$post_id')";
                $query2="UPDATE posts SET likes = likes + 1 WHERE id = '$post_id'";
                // $query3="UPDATE users SET num_likes = num_likes + 1 WHERE username = '$post_by'";
                $query3="UPDATE users SET num_likes = num_likes + 1 WHERE username = '$active_user'";
                break;
            case 'unlike':
                $query1="DELETE FROM likes WHERE like_by='$active_user' AND post_id='$post_id'";
                $query2="UPDATE posts SET likes = likes - 1 WHERE id = '$post_id'";
                // $query3="UPDATE users SET num_likes = num_likes - 1 WHERE username = '$post_by'";
                $query3="UPDATE users SET num_likes = num_likes - 1 WHERE username = '$active_user'";
                break;
            default:
                break;
        }
        
        mysqli_query($connect, $query1);
        mysqli_query($connect, $query2);
        mysqli_query($connect, $query3);
        exit(0);
    }
?>