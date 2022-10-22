<?php 
	include "config/base_url.php";
	include "config/db.php";
	include "common/time.php";

	if(!isset($_GET["id"])) {
		header("Location:$BASE_URL/index.php");
		exit();
	}

	$id = $_GET["id"];
	$query_blog = mysqli_query($con,
	"SELECT b.*, c.name, u.nickname, u.ava FROM blogs b
	LEFT OUTER JOIN categories c ON c.id=b.category_id
	LEFT OUTER JOIN users u ON u.id=b.author_id
	WHERE b.id=$id");

	if(mysqli_num_rows($query_blog) == 0) {
		header("Location:$BASE_URL/index.php");
		exit();
	}

	$blog = mysqli_fetch_assoc($query_blog);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Профиль</title>
    <?php include "views/head.php"; ?>
</head>
<body
data-baseurl="<?=$BASE_URL?>"
data-authorid="<?=$blog["author_id"]?>"
data-blogid="<?=$blog["id"]?>"
>

<?php include "views/header.php";?>

<section class="container page">
	<div class="page-content blog-details">
		<div class="blogs">
			<div class="blog-item">
				<img class="blog-item--img img-details" src="<?=$blog["img"]?>" alt="">

                <div class="blog-info blog--comment">
					<span class="link date-link">
						<img src="images/date.svg" alt="">
						<?=to_time_ago($blog["date"])?>
					</span>
					<a <?php if(isset($_SESSION["user_id"])) { ?> onclick="likePress(<?=$number?>)"  href="<?=$BASE_URL?>/api/like/edit.php?user_id=<?=$_SESSION["user_id"]?>&blog_id=<?=$blog["id"]?>&details=1"  <?php } else { ?> onclick="openModalWindow()" <?php } ?> class="link like-link">
						<?php
								$total = 0;
								if(isset($blog["id"]) && intval($blog["id"])) {
									$check = mysqli_prepare($con, "SELECT * FROM likes WHERE blog_id=?");
									mysqli_stmt_bind_param($check, "i", $blog["id"]);
									mysqli_stmt_execute($check);
									$likes = mysqli_stmt_get_result($check);
									$img = '<img class="like-img" src="images/lightning.svg">';

									if(mysqli_num_rows($likes) > 0) {
											while($row = mysqli_fetch_assoc($likes)) {
												$total++;
												if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $row["like_author"]) {
													$img = '<img class="like-img" src="images/lightning_solid.svg">';
												} 
											
										} 
									} 
									echo $img;
								}
							?>
							<?= $total ?>
						</a>
					<span class="link">
						<img src="images/forums.svg" alt="">
						<?=$blog["name"]?>
					</span>
					<a class="user-link link" href="<?=$BASE_URL?>/profile.php?nickname=<?=$blog["nickname"]?>">
							<img src="images/person.svg" alt="">
							<?=$blog["nickname"]?> 
					</a>
				</div>

				<div class="blog-header">
					<h3>
						<p class="link date-link adaptive"><?=to_time_ago_adaptive($blog["date"])?></p>
						<?=$blog["title"]?>
					</h3>
				</div>
				<p class="blog-desc">
					<?=$blog["description"]?>
				</p>
			</div>
		</div>

        <div id="comments" class="comments">
        </div>
		<?php 
			if(isset($_SESSION["user_id"])) {
		?>
			<span class="comment-add">
                <textarea id="textarea" class="input input-textarea" placeholder="Введитe текст комментария"></textarea>
                <button id="add-btn" class="button">Отправить</button>
            </span>
		<?php
			} else {
		?>
            <span class="comment-warning">
                Чтобы оставить комментарий <a href="<?=$BASE_URL?>/register.php">зарегистрируйтесь</a> , или  <a onclick="openModalWindow()">войдите</a>  в аккаунт.
            </span>
		<?php 
			}
		?>
	</div>
</section>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="js/comments.js"></script>
</body>
</html>