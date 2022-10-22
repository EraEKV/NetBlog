<?php
    include "../../config/db.php";
    include "../../config/base_url.php";

    if(isset($_GET["nickname"]) && strlen($_GET["nickname"]) > 0) {
        $prep = mysqli_prepare($con,
		"SELECT b.*, u.nickname, c.name FROM blogs b
		LEFT OUTER JOIN users u ON u.id = b.author_id
		LEFT OUTER JOIN categories c ON c.id = b.category_id
		WHERE u.nickname=?");
		mysqli_stmt_bind_param($prep, "s", $_GET["nickname"]);
		mysqli_stmt_execute($prep);
		$blogs = mysqli_stmt_get_result($prep);
        // $comments = mysqli_query($con, "SELECT CEIL(COUNT(*)) as total FROM comments c WHERE c.blog_id=".$blog["id"]);
        // $comment = mysqli_fetch_assoc($comments);
        $res = array();
        if(mysqli_num_rows($blogs) > 0) {
            while($blog = mysqli_fetch_assoc($blogs)) {
                $res[] = $blog;
            }
        }

        echo json_encode($res);
    } else {
        header("Location:$BASE_URL/profile.php?nickname=".$_GET["nickname"])."&error=1";
    }
?>