<?php

class Post{
    private $user_obj;
    private $connect;

    //get user details from db
    public function __construct($connect, $user)
    {
        $this->connect = $connect;
        $this->user_obj = new User($connect,$user);
    }

    //pass post data to db 
    public function submitPost($caption, $post_img ,$tag)
    {
        //remove htmltags
        $caption = strip_tags($caption);

        //ignore quotes to avoid query bug
        $caption = mysqli_real_escape_string($this->connect, $caption);

        //delete spaces
        $check_empty = preg_replace("/\s+/", "", $caption); 

        if($check_empty != ""){
            //get time &date
            $post_date = date("Y-m-d H:i:s");
            //get username 
            $post_by = $this->user_obj->getUsername();

            //send post to db 
            $query = mysqli_query($this->connect, "INSERT INTO posts VALUES ('', '$caption', '$post_img', '$post_by', '$post_date', 'no', '0', '$tag')");
            //return post id
            $post_id = mysqli_insert_id($this->connect);

            //update post count
            $post_count = $this->user_obj->getPostCount();
            $post_count++;
            $update_data = mysqli_query($this->connect, "UPDATE users SET num_post='$post_count' WHERE username='$post_by'");

        }
    }

    public function loadPost(){
        $str = "";
        $query = mysqli_query($this->connect, "SELECT *FROM posts WHERE deleted='no' ORDER BY id DESC");

        while($data = mysqli_fetch_array($query)){
            $id = $data["id"];
            $post_caption = $data["post_caption"];
            $post_img = $data["post_img"];
            $post_by = $data["post_by"];
            $post_date = $data["post_date"];
            $post_tag = $data["tag"];

            $user_query = mysqli_query($this->connect, "SELECT username, profile_pic FROM users WHERE username ='$post_by'");
            $user_data = mysqli_fetch_array($user_query);
            $profile_pic = $user_data["profile_pic"];

            $check_comments = mysqli_query($this->connect, "SELECT * FROM comments WHERE post_id='$id'");
            $comments_count = mysqli_num_rows($check_comments);

            $check_likes = mysqli_query($this->connect, "SELECT * FROM likes WHERE post_id='$id'");
            $likes_count = mysqli_num_rows($check_likes);

            //check if posts already liked by user
            $check_if_liked = $this->user_obj->checkLike($id);
            if($check_if_liked == 1){ //if not liked, make icon hollow
                $like_icon = "<i class='bi bi-heart'></i>";
            }else{//if liked, make icon solid
                $like_icon = "<i class='bi bi-heart-fill'></i>";
            }

            //check if post is posted by active user
            $user = $this->user_obj->getUsername();
            if($post_by == $user){
                $edit_del_btn = "<div class='option-btn'>
                                    <i class='edit-btn bi bi-pencil-fill bg-pink brd-outline btn btn-outline-dark' data-bs-toggle='modal' data-bs-target='#editModal' data-id='$id'></i>
                                    <i class='del-btn bi bi-trash-fill bg-pink brd-outline btn btn-outline-dark' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='$id'></i>
                                </div>";
            }else{
                $edit_del_btn = "";
            }
 
            $current_date_time = date("Y-m-d H:i:s");
            $start_date = new DateTime($post_date); //post date 
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

            $str .= "<div class='col-sm-4 mb-4'>
                        <div class='card brd-outline'>
                            <div style='position: relative;'>
                                <img src='$post_img' class='card-img-top'>
                                $edit_del_btn
                            </div>
                            <div class='card-body row'>
                                <div class='col'>
                                    <div class=' row card-text'>
                                        <div class='col-2'><img src='$profile_pic' class='img-fluid img-pfp'></div>
                                        
                                        <div class='col-5 card-details'>
                                            <div class='row'><a href='$post_by' class='post-by'>$post_by</a></div>
                                            <div class='row'><p class='post-time'>$timespan_msg</p></div>
                                        </div>

                                        <div class='col-5 card-tag'>
                                            <a href='search_page.php?post_tag=$post_tag' class='post-tag brd-outline btn-outline-dark'>$post_tag</a>
                                        </div>
                                    </div>

                                    <div class='post-caption row card-text'>
                                        <p>$post_caption</p>
                                    </div>

                                    <div class='row'>
                                        <div class='col'>
                                            <a class='likebtn btn bg-pink btn-outline-dark w-100' data-id='$id'>$like_icon&nbsp;&nbsp;<span class='like-count'>$likes_count</span></a>
                                        </div>
                                        <div class='col'>
                                            <a class='btn bg-pink btn-outline-dark w-100' onclick='getId($id)' data-bs-toggle='offcanvas' href='#offcanvas_comments' role='button' aria-controls='offcanvas_comments'>
                                                <i class='bi bi-chat-right-quote'></i>&nbsp;&nbsp;<span id='comment-count-$id'>$comments_count</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
        }
        
        echo $str;
    }

    public function loadTagPost($tag){
        $str = "";
        $query = mysqli_query($this->connect, "SELECT * FROM posts WHERE tag='$tag' ORDER BY id DESC");

        while($data = mysqli_fetch_array($query)){
            $id = $data["id"];
            $post_caption = $data["post_caption"];
            $post_img = $data["post_img"];
            $post_by = $data["post_by"];
            $post_date = $data["post_date"];
            $post_tag = $data["tag"];

            $user_query = mysqli_query($this->connect, "SELECT username, profile_pic FROM users WHERE username ='$post_by'");
            $user_data = mysqli_fetch_array($user_query);
            $profile_pic = $user_data["profile_pic"];

            $check_comments = mysqli_query($this->connect, "SELECT * FROM comments WHERE post_id='$id'");
            $comments_count = mysqli_num_rows($check_comments);

            $check_likes = mysqli_query($this->connect, "SELECT * FROM likes WHERE post_id='$id'");
            $likes_count = mysqli_num_rows($check_likes);

            //check if posts already liked by user
            $check_if_liked = $this->user_obj->checkLike($id);
            if($check_if_liked == 1){ //if not liked, make icon hollow
                $like_icon = "<i class='bi bi-heart'></i>";
            }else{//if liked, make icon solid
                $like_icon = "<i class='bi bi-heart-fill'></i>";
            }

            //check if post is posted by active user
            $user = $this->user_obj->getUsername();
            if($post_by == $user){
                $edit_del_btn = "<div class='option-btn'>
                                    <i class='edit-btn bi bi-pencil-fill bg-pink brd-outline btn btn-outline-dark' data-bs-toggle='modal' data-bs-target='#editModal' data-id='$id'></i>
                                    <i class='del-btn bi bi-trash-fill bg-pink brd-outline btn btn-outline-dark' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='$id'></i>
                                </div>";
            }else{
                $edit_del_btn = "";
            }
 
            $current_date_time = date("Y-m-d H:i:s");
            $start_date = new DateTime($post_date); //post date 
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

            $str .= "<div class='col-sm-4 mb-4'>
                        <div class='card brd-outline'>
                            <div style='position: relative;'>
                                <img src='$post_img' class='card-img-top'>
                                $edit_del_btn
                            </div>
                            <div class='card-body row'>
                                <div class='col'>
                                    <div class=' row card-text'>
                                        <div class='col-2'><img src='$profile_pic' class='img-fluid img-pfp'></div>
                                        
                                        <div class='col-5 card-details'>
                                            <div class='row'><a href='$post_by' class='post-by'>$post_by</a></div>
                                            <div class='row'><p class='post-time'>$timespan_msg</p></div>
                                        </div>

                                        <div class='col-5 card-tag'>
                                            <a href='search_page.php?post_tag=$post_tag' class='post-tag brd-outline btn-outline-dark'>$post_tag</a>
                                        </div>
                                    </div>

                                    <div class='post-caption row card-text'>
                                        <p>$post_caption</p>
                                    </div>

                                    <div class='row'>
                                        <div class='col'>
                                            <a class='likebtn btn bg-pink btn-outline-dark w-100' data-id='$id'>$like_icon&nbsp;&nbsp;<span class='like-count'>$likes_count</span></a>
                                        </div>
                                        <div class='col'>
                                            <a class='btn bg-pink btn-outline-dark w-100' onclick='getId($id)' data-bs-toggle='offcanvas' href='#offcanvas_comments' role='button' aria-controls='offcanvas_comments'>
                                                <i class='bi bi-chat-right-quote'></i>&nbsp;&nbsp;<span id='comment-count-$id'>$comments_count</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
        }
        
        echo $str;
    }
    
    public function loadProfilePost($user_profile){
        $str = "";
        $query = mysqli_query($this->connect, "SELECT * FROM posts WHERE deleted='no' AND post_by='$user_profile' ORDER BY id DESC");

        while($data = mysqli_fetch_array($query)){
            $id = $data["id"];
            $post_caption = $data["post_caption"];
            $post_img = $data["post_img"];
            $post_by = $data["post_by"];
            $post_date = $data["post_date"];
            $post_tag = $data["tag"];

            $user_query = mysqli_query($this->connect, "SELECT username, profile_pic FROM users WHERE username ='$post_by'");
            $user_data = mysqli_fetch_array($user_query);
            $profile_pic = $user_data["profile_pic"];

            $check_comments = mysqli_query($this->connect, "SELECT * FROM comments WHERE post_id='$id'");
            $comments_count = mysqli_num_rows($check_comments);

            $check_likes = mysqli_query($this->connect, "SELECT * FROM likes WHERE post_id='$id'");
            $likes_count = mysqli_num_rows($check_likes);

            //check if posts already liked by user
            $check_if_liked = $this->user_obj->checkLike($id);
            if($check_if_liked == 1){ //if not liked, make icon hollow
                $like_icon = "<i class='bi bi-heart'></i>";
            }else{//if liked, make icon solid
                $like_icon = "<i class='bi bi-heart-fill'></i>";
            }
 
            $current_date_time = date("Y-m-d H:i:s");
            $start_date = new DateTime($post_date); //post date 
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

            $str .= "<div class='col-sm-4 mb-4'>
                        <div class='card brd-outline'>
                            <div style='position: relative;'>
                                <img src='$post_img' class='card-img-top'>
                                <div class='option-btn'>
                                    <i class='bi bi-pencil-fill bg-pink brd-outline btn btn-outline-dark' data-bs-toggle='modal' data-bs-target='#exampleModal'></i>
                                    <i class='bi bi-trash-fill bg-pink brd-outline btn btn-outline-dark'></i>
                                </div>
                            </div>
                            <div class='card-body row'>
                                <div class='col'>
                                    <div class=' row card-text'>
                                        <div class='col-2'><img src='$profile_pic' class='img-fluid img-pfp'></div>
                                        
                                        <div class='col-5 card-details'>
                                            <div class='row'><a href='$post_by' class='post-by'>$post_by</a></div>
                                            <div class='row'><p class='post-time'>$timespan_msg</p></div>
                                        </div>

                                        <div class='col-5 card-tag'>
                                            <a href='search_page.php' class='post-tag brd-outline btn-outline-dark'>#$post_tag</a>
                                        </div>
                                    </div>

                                    <div class='post-caption row card-text'>
                                        <p>$post_caption</p>
                                    </div>

                                    <div class='row'>
                                        <div class='col'>
                                            <a class='likebtn btn bg-pink btn-outline-dark w-100' data-id='$id'>$like_icon&nbsp;&nbsp;<span class='like-count'>$likes_count</span></a>
                                        </div>
                                        <div class='col'>
                                            <a class='btn bg-pink btn-outline-dark w-100' onclick='getId($id)' data-bs-toggle='offcanvas' href='#offcanvas_comments' role='button' aria-controls='offcanvas_comments'>
                                                <i class='bi bi-chat-right-quote'></i>&nbsp;&nbsp;<span id='comment-count-$id'>$comments_count</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
        }
        
        echo $str;
    }

    public function loadLikedPost($user_profile){
        $str = "";
        $like_query = mysqli_query($this->connect, "SELECT post_id FROM likes WHERE like_by='$user_profile'");

        while($like_data = mysqli_fetch_array($like_query)){
            $like_id = $like_data["post_id"];
            $query = mysqli_query($this->connect, "SELECT * FROM posts WHERE deleted='no' AND id='$like_id' ORDER BY id DESC");
            while($data = mysqli_fetch_array($query)){
                $id = $data["id"];
                $post_caption = $data["post_caption"];
                $post_img = $data["post_img"];
                $post_by = $data["post_by"];
                $post_date = $data["post_date"];
                $post_tag = $data["tag"];

                $user_query = mysqli_query($this->connect, "SELECT username, profile_pic FROM users WHERE username ='$post_by'");
                $user_data = mysqli_fetch_array($user_query);
                $profile_pic = $user_data["profile_pic"];

                $check_comments = mysqli_query($this->connect, "SELECT * FROM comments WHERE post_id='$id'");
                $comments_count = mysqli_num_rows($check_comments);

                $check_likes = mysqli_query($this->connect, "SELECT * FROM likes WHERE post_id='$id'");
                $likes_count = mysqli_num_rows($check_likes);

                //check if posts already liked by user
                $check_if_liked = $this->user_obj->checkLike($id);
                if($check_if_liked == 1){ //if not liked, make icon hollow
                    $like_icon = "<i class='bi bi-heart'></i>";
                }else{//if liked, make icon solid
                    $like_icon = "<i class='bi bi-heart-fill'></i>";
                }
    
                $current_date_time = date("Y-m-d H:i:s");
                $start_date = new DateTime($post_date); //post date 
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

                $str .= "<div class='col-sm-4 mb-4'>
                            <div class='card brd-outline'>
                                <img src='$post_img' class='card-img-top'>
                                <div class='card-body row'>
                                    <div class='col'>
                                        <div class=' row card-text'>
                                            <div class='col-2'>
                                                <img src='$profile_pic' class='img-fluid img-pfp'>
                                            </div>
                                            
                                            <div class='col-5 card-details'>
                                                <div class='row'><a href='$post_by' class='post-by'>$post_by</a></div>
                                                <div class='row'><p class='post-time'>$timespan_msg</p></div>
                                            </div>

                                            <div class='col-5 card-tag'>
                                                <a href='search_page.php' class='post-tag brd-outline btn btn-outline-dark'>#$post_tag</a>
                                            </div>
                                        </div>

                                        <div class='post-caption row card-text'>
                                            <p>$post_caption</p>
                                        </div>

                                        <div class='row'>
                                            <div class='col'>
                                                <a class='likebtn btn bg-pink btn-outline-dark w-100' data-id='$id'>$like_icon&nbsp;&nbsp;<span class='like-count'>$likes_count</span></a>
                                            </div>
                                            <div class='col'>
                                                <a class='btn bg-pink btn-outline-dark w-100' onclick='getId($id)' data-bs-toggle='offcanvas' href='#offcanvas_comments' role='button' aria-controls='offcanvas_comments'>
                                                    <i class='bi bi-chat-right-quote'></i>&nbsp;&nbsp;<span id='comment-count-$id'>$comments_count</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
            }
            
        }          
            echo $str;                 
    }
}

?>