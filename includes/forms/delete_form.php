<script>
    $(document).ready(function(){
        
        $('.del-btn').on('click',function(){

            var post_id = $(this).data('id');

            console.log("delete btn clicked" + post_id);

            $("#del_id").val(post_id);
        });
    });

</script>

<?php

    if (isset($_POST["del_submit"])) {

        $del_id=$_POST["del_id"];
        $query = "DELETE FROM posts WHERE id = '$del_id'";
        
        mysqli_query($connect, $query);
    }
?>