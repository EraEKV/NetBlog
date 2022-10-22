<?php 
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(isset($_GET["blog_id"]) &&  intval($_GET["blog_id"])) {
        $blog_id = $_GET["blog_id"];

        $comments = mysqli_query($con, "SELECT CEIL(COUNT(*)) as total FROM comments c WHERE c.blog_id=".$blog_id);
        $comment = mysqli_fetch_assoc($comments);

        $com = $comment["total"];

        echo json_encode($com);
    }
?>