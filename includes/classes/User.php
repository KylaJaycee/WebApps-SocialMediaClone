<?php

class User{
    private $user;
    private $connect;

    //get user details from db
    public function __construct($connect, $user)
    {
        $this->connect = $connect;
        $user_details = mysqli_query($connect, "SELECT * FROM users WHERE username='$user'");
        $this->user = mysqli_fetch_array($user_details);
    }
    
    //get username 
    public function getUsername(){
        return $this->user["username"];
    }

    //get profile picture 
    public function getpfp(){
        $username = $this->user["username"];
        $query = mysqli_query($this->connect, "SELECT profile_pic FROM users WHERE username='$username'");
        $data = mysqli_fetch_array($query);
        return $data["profile_pic"];

    }

    //get total post count
    public function getPostCount(){
        $username = $this->user["username"];
        $query = mysqli_query($this->connect, "SELECT num_post FROM users WHERE username='$username'");
        $data = mysqli_fetch_array($query);
        return $data["num_post"];
    }

    //get total like count
    public function getLikeCount(){
        $username = $this->user["username"];
        $query = mysqli_query($this->connect, "SELECT num_like FROM users WHERE username='$username'");
    }

    //get total friend count

    //get data account made

    //get all posts made

    //check if user liked post
    public function checkLike($post_id){
        $username = $this->user["username"];
        $query = mysqli_query($this->connect, "SELECT * FROM likes WHERE post_id='$post_id' and like_by='$username'");
        $check = mysqli_num_rows($query);

        if($check < 1){
            return 1; //not liked
        }else{
            return 0; //liked
        }
    }
}
?>