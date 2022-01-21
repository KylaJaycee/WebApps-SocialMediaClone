<?php
    require "config/config.php";
    include("includes/classes/User.php");
    include("includes/classes/Post.php");
    
    //check if a user is already logged in
    if(isset($_SESSION["username"])){
        $active_user = $_SESSION["username"];
        $user_query = mysqli_query($connect, "SELECT * FROM users WHERE username='$active_user'");
        $user_details = mysqli_fetch_array($user_query);
    }else{
        //else load login page
        header("Location: login_page.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
    <script src="assets/js/bootstrap.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/stylesheet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title>Menu</title>
    <script>
        function view() {
            img.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</head>
<body>
    <main>
        <div class="d-flex flex-column flex-shrink-0 p-3 menu " style="width: 18%; min-width: 170px;">
            <a href="index.php" class="w-100 link-light text-decoration-none logo-title">Horticulture</a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-light btn-outline-dark btn-lg w-100" data-bs-toggle="modal" data-bs-target="#post-modal">
                        <i class="bi bi-flower3"></i>Post
                    </button>
                </li>
                <hr>
                <li>
                    <a href="index.php" class="nav-link link-light">
                        <i class="bi bi-house-door"></i>&nbsp;&nbsp;Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo $active_user?>" class="nav-link link-light">
                        <i class="bi bi-person"></i>&nbsp;&nbsp;Profile
                    </a>
                </li>
                <li>
                    <a href="search_page.php" class="nav-link link-light">
                        <i class="bi bi-search"></i>&nbsp;&nbsp;Explore Tags
                    </a>
                </li>
            </ul>
            <hr>
            
            <a href="settings_page.php" class="nav-link link-light">
                Settings
            </a>
            <a href="includes/logout.php" class="nav-link link-light">
                Log Out
            </a>
        </div>
    </main>

    <!-- Post Modal -->
    <div class="modal fade" id="post-modal" tabindex="-1" aria-labelledby="post-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="post-modalLabel">Share your plants :)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="w-75  mx-auto my-0">
                    <img id="img" src="assets/images/image_placeholder.png" class='img-fluid'/><br/>
                </div>
                <form action="index.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="file" name="post_img" class="form-control"  onchange="view()"required>
                        <textarea name="post_caption" id="post_caption" rows="2" placeholder="Add a caption" class="form-control"></textarea>
                        <input type="text" name="post_tag" id="post_tag" class="form-control" placeholder="Add a tag (limit 1)">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="post_submit" id="post_button" value="Post" class="btn bg-pink btn-outline-dark w-100">
                    </div>
                </form> 
            </div>
        </div>
    </div>

    <!-- Edt Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <textarea name="edit_caption" id="edit_caption" rows="2" placeholder="Add a caption" class="form-control"></textarea>
                        <input type="text" name="edit_tag" id="edit_tag" class="form-control" placeholder="Add a tag (limit 1)">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" name="edit_submit" id="edit_button" value="Save Edit" class="btn bg-pink btn-outline-dark">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <div class="modal-body">
                        Are you sure?
                        <input type="hidden" name="del_id" id="del_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" name="del_submit" id="del_button" value="Delete Post" class="btn bg-pink btn-outline-dark">
                    </div>
                </form>
            </div>
        </div>
    </div>