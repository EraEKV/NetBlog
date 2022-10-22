<?php
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(isset($_GET["blog_id"]) && intval($_GET["blog_id"])) {
        $blog_id = $_GET["blog_id"];

        $check = mysqli_prepare($con, "SELECT * FROM likes WHERE blog_id=?");
        mysqli_stmt_bind_param($check, "i", $blog_id);
        mysqli_stmt_execute($check);
        $likes = mysqli_stmt_get_result($check);
        
        $like = array();
        
        if(mysqli_num_rows($likes) > 0) {
            while($row = mysqli_fetch_assoc($likes)) {
                $like[] = $row;
            }
        }

        echo json_encode($like);
    }
?>