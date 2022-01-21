<?php

require "includes/menu.php";
require "includes/forms/post_form.php";
require "includes/forms/delete_form.php";
require "includes/forms/edit_form.php";
require "detailedposts_view.php";
require "likes.php";

?>
    <div class="content">
        <div class="row" data-masonry='{"percentPosition": true }'>
            <?php
                $post = new Post($connect, $active_user);
                $post-> loadPost();
            ?>
        </div>
    </div>
</div>

</body>
</html>