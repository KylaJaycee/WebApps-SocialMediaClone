<?php
require "includes/menu.php";
require "includes/forms/post_form.php";
require "includes/forms/delete_form.php";
require "includes/forms/edit_form.php";
require "detailedposts_view.php";
require "likes.php";

if(isset($_GET["profile_username"])){
    $username = $_GET["profile_username"];
    $query = mysqli_query($connect, "SELECT * FROM users WHERE username = '$username'");
    $data = mysqli_fetch_array($query);
}
?>
    <div class="content">
        <div class="user-data card brd-outline">
            <div class="row card-body">
                <div class="col-2">
                    <img src="<?php echo $data["profile_pic"];?>" class='img-fluid img-pfp' alt="">
                </div>
                <div class="col-10 d-grid">
                    <h1 class="row">
                        <?php echo $username; ?>
                    </h1>
                    <p class="row">
                        Joined <?php echo $data["date_made"]; ?>
                    </p>
                    <div class="row">
                        <div class="col">
                            <div class="row">Posts</div>
                            <div class="row"><?php echo $data["num_post"]; ?></div>
                        </div>
                        <div class="col">
                            <div class="row">Likes</div>
                            <div class="row"><?php echo $data["num_likes"]?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs nav-fill my-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab" aria-controls="posts" aria-selected="true">Posts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="likes-tab" data-bs-toggle="tab" data-bs-target="#likes" type="button" role="tab" aria-controls="likes" aria-selected="false">Likes</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                <div class="row" data-masonry='{"percentPosition": true }'>
                    <?php
                        $post = new Post($connect, $active_user);
                        $post-> loadProfilePost($username);
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="likes" role="tabpanel" aria-labelledby="likes-tab">
                <div class="row" data-masonry='{"percentPosition": true }'>
                    <?php
                        $post = new Post($connect, $active_user);
                        $post-> loadLikedPost($username);
                    ?>
                </div>
            </div>
        </div>

    </div>
</body>
</html>