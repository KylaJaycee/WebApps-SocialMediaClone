<script>
    $(document).ready(function(){
        
        $('.edit-btn').on('click',function(){

            var post_id = $(this).data('id');
            del = "delete";
            console.log("edit btn clicked" + post_id);

            // $caption = $(this).parent('.option-btn').parent().siblings('.card.body').children('.col').children('.post-caption').children('p').text();
            // $caption = $(this).parent().parent().siblings('.card.body').children('.col').children('.post-caption').children().text();
            // $caption = $(this).parents('.card').children('.post-caption').text();
            $caption = $(this).closest('div.card').children('.card.body').children('.col').children('.post-caption').children('p').text();
            console.log("caption: " + $caption);
            $("#edit_id").val(post_id);

            $.ajax({
                url: 'index.php',
                type: 'post',
                data: {
                    'del': del,
                    'post_id': post_id
                },
            })
        });
    });

</script>

<?php
    if (isset($_POST["edit_submit"])) {

        $edit_id=$_POST["edit_id"];
        $edit_caption = $_POST["edit_caption"];
        $edit_tag = $_POST["edit_tag"];
        $query = "UPDATE posts SET post_caption ='$edit_caption', tag = '$edit_tag' where id = '$edit_id'";
        mysqli_query($connect, $query);
    }
?>