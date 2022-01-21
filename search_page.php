<?php
    require "includes/menu.php";
    require "includes/forms/post_form.php";

?>   
    <div class="content">

        <?php
        if(isset($_GET["post_tag"])){
            $post_tag = $_GET["post_tag"];
        ?>
            <h1 class="my-3 text-center post-tag bg-pink brd-outline btn-outline-dark"><?php echo $post_tag; ?></h1>
            <div class="row" data-masonry='{"percentPosition": true }'>
                <?php
                    $post = new Post($connect, $active_user);
                    $post-> loadTagPost($post_tag);
                ?>
            </div>
        <?php
        }else{
            echo "<h1 class='my-3 text-center'>Explore Tags</h1>";
            $query = mysqli_query($connect,"SELECT DISTINCT tag FROM posts");

            while($data = mysqli_fetch_array($query)){
                $tag = $data['tag'];
                echo "<a href='search_page.php?post_tag=$tag' class='search-tag bg-pink brd-outline btn-outline-dark my-1 text-center'>$tag</a>";
            }
        }
        ?>
        
    </div>
    
</body>
</html>