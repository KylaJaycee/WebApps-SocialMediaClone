<script>
    function getId(id){
        console.log(id);
        document.getElementById("comment_iframe").src = "comments.php?post_id="+id;
    }
</script>

<div class='bg-beige offcanvas offcanvas-end' tabindex='-1' id='offcanvas_comments' aria-labelledby='offcanvas_commentsLabel'>
    <div class='offcanvas-header'>
        <h5 class='offcanvas-title' id='offcanvas_commentsLabel'>Comments</h5>
        <button type='button' class='btn-close text-reset' data-bs-dismiss='offcanvas' aria-label='Close'></button>
    </div>
    <div class='offcanvas-body'>
        <div style='height:100%;' class='post_comment'>
            <iframe src='comments.php?post_id=$id' id='comment_iframe' frameborder='0' style='height:100%; width:100%;'></iframe>
        </div>
    </div>   
</div>



