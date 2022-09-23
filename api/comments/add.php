<?php
    include "../../config/db.php";
    include "../../config/base_url.php";

    $data = json_decode(file_get_contents("php://input"), true);
    
    if(isset($data["text"], $data["blog_id"])
    && strlen($data["text"]) > 0
    && intval($data["blog_id"])) {
        $text = $data["text"];
        $blog_id = $data["blog_id"];
        session_start();
        $author_id = $_SESSION["user_id"];

        $prep = mysqli_prepare($con, "INSERT INTO comments(text, blog_id, author_id) VALUES(?, ?, ?)");
        mysqli_stmt_bind_param($prep, "sii", $text, $blog_id, $author_id);
        mysqli_stmt_execute($prep);
    } else {
        header("Location:$BASE_URL/blog-details.php?id=".$_GET["id"]."&error=15");
    }
?>