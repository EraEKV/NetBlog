<?php
    include "../../config/base_url.php";
    include "../../config/db.php";

    if(isset($_GET["user_id"], $_GET["blog_id"]) 
    && intval($_GET["user_id"]) 
    && intval($_GET["user_id"])
    && (isset($_GET["page"]) || isset($_GET["profile"]) || isset($_GET["details"]))
    ) {
        $user_id = $_GET["user_id"];
        $blog_id = $_GET["blog_id"];

        $check = mysqli_prepare($con, "SELECT * FROM likes WHERE blog_id=?");
        mysqli_stmt_bind_param($check, "i", $_GET["blog_id"]);
        mysqli_stmt_execute($check);
        $likes = mysqli_stmt_get_result($check);
        $check = "";

        while($row = mysqli_fetch_assoc($likes)) {
            if($row["like_author"] == $user_id) {
                $check = "true";
            } 
        }
        
        if($check != "") {
            $delete_prep = mysqli_prepare($con, "DELETE FROM likes WHERE like_author=? AND blog_id=?");
            mysqli_stmt_bind_param($delete_prep, "ii", $user_id, $blog_id);
            mysqli_stmt_execute($delete_prep);
        } else {
            $add_prep = mysqli_prepare($con, "INSERT INTO likes (like_author, blog_id) VALUES(?, ?)");
            mysqli_stmt_bind_param($add_prep, "ii", $user_id, $blog_id);
            mysqli_stmt_execute($add_prep);
        }
        
        if(isset($_GET["page"],$_GET["cat_id"])) {
            header("Location:$BASE_URL/index.php?page=".$_GET["page"]."&cat_id=".$_GET["cat_id"]);
        } else if(isset($_GET["page"], $_GET["q"])) {
            header("Location:$BASE_URL/index.php?page=".$_GET["page"]."&q=".$_GET["q"]);
        } else if(isset($_GET["page"], $_GET["q"], $_GET["cat_id"])) {
            header("Location:$BASE_URL/index.php?page=".$_GET["page"]."&cat_id=".$_GET["cat_id"]."&q=".$_GET["q"]);
        } else if(isset($_GET["page"])) {
            header("Location:$BASE_URL/index.php?page=".$_GET["page"]);
        } else if(isset($_GET["details"])) {
            header("Location:$BASE_URL/blog-details.php?id=$blog_id");
        }
    }
?>

