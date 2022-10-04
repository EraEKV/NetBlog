<?php 
	include "config/base_url.php"; 
	include "config/db.php"; 
	include "common/time.php"; 

	if(isset($_GET["nickname"])) {
		$prep = mysqli_prepare($con,
		"SELECT b.*, u.nickname, c.name FROM blogs b
		LEFT OUTER JOIN users u ON u.id = b.author_id
		LEFT OUTER JOIN categories c ON c.id = b.category_id
		WHERE u.nickname=?");
		mysqli_stmt_bind_param($prep, "s", $_GET["nickname"]);
		mysqli_stmt_execute($prep);
		$blogs = mysqli_stmt_get_result($prep);
	} else {
		header("Location:$BASE_URL/index.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Профиль</title>
	<?php include "views/head.php"; ?>
</head>
<body>

<header class="header container">
	<div class="header-logo">
	    <a href="index.php?page=1">Net<span>Blog</span></a>	
	</div>
	<div class="header-search">
		<input type="text" class="input-search" placeholder="Поиск по блогам">
		<button class="button button-search">
			<img src="images/search.svg" alt="">	
			Найти
		</button>
	</div>
	<div>
		<?php
			if(isset($_SESSION["nickname"])) {
				$user_prep = mysqli_prepare($con, "SELECT * FROM users WHERE id=?");
				mysqli_stmt_bind_param($user_prep, "s", $_SESSION["user_id"]);
				mysqli_stmt_execute($user_prep);
				$user_info = mysqli_stmt_get_result($user_prep);
				$user = mysqli_fetch_assoc($user_info);
		?>
				<a href="profile.php?nickname=<?=$_SESSION["nickname"]?>">
					<img class="avatar" src="<?=$BASE_URL?>/<?=$user["ava"]?>" alt="Avatar">
				</a>
		<?php
			} else {
		?>
			<div class="button-group">
				<a href="register.php" class="button">Регистрация</a>
				<a href="login.php" class="button">Вход</a>
			</div>
		<?php
			}
		?>
		
	</div>
</header>

<section class="container page">
	<div class="page-content">
		<div class="page-header">
			<?php
				$user_prep = mysqli_prepare($con,"SELECT * FROM users WHERE nickname=?");
				mysqli_stmt_bind_param($user_prep, "s", $_GET["nickname"]);
				mysqli_stmt_execute($user_prep);
				$user_info = mysqli_stmt_get_result($user_prep);
				$user = mysqli_fetch_assoc($user_info);
				$number = 0;
				
				if(isset($user["nickname"]) && isset($_SESSION["nickname"]) && $user["nickname"] == $_SESSION["nickname"]) {
			?>
				<h2>Мои блоги</h2>
				<a class="button" href="newblog.php">Новый блог</a>
			<?php
				} else {
			?>
				<h2>Блоги <?=$user["nickname"]?></h2>
			<?php
				}
			?>
		</div>

		<div class="blogs">
			<?php
				if(mysqli_num_rows($blogs)) {
					while($blog = mysqli_fetch_assoc($blogs)) {
			?>
					<div class="blog-item">
					<a class="blog_link" href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>">
						<img class="blog-item--img" src="<?=$BASE_URL?>/<?=$blog["img"]?>" alt="">
					</a>
					<div class="blog-info">
						<span class="link">
							<img src="images/date.svg" alt="">
							<?=to_time_ago($blog["date"])?>
							<!-- to_time_ago показывает как давно загрузился файл -->
						</span>
						<span class="link">
							<img src="images/visibility.svg" alt="">
							21
						</span>
						<a class="link comments-link" href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>">
							<img src="images/message.svg" alt="">
							4
						</a>
						<span class="link">
							<img src="images/forums.svg" alt="">
							<?=$blog["name"]?>
						</span>
						<a class="link">
							<img src="images/person.svg" alt="">
							<?=$blog["nickname"]?>
						</a>
					</div>
					<div class="blog-header">
						<h3>
							<?=$blog["title"]?>
						</h3>
						<?php
							if(isset($user["nickname"]) && isset($_SESSION["nickname"]) && $blog["nickname"] == $_SESSION["nickname"]) {
						?>
							<span class="link">
								<img src="images/dots.svg" alt="">
								Еще

								<ul class="dropdown">
									<li> <a href="<?=$BASE_URL?>/editblog.php?id=<?=$blog["id"]?>">Редактировать</a> </li>
									<li><a href="<?=$BASE_URL?>/api/blog/delete_blog.php?id=<?=$blog["id"]?>" class="danger">Удалить</a></li>
								</ul>
							</span>
						<?php
							}
						?>

					</div>
					<p class="blog-desc">
						<?=$blog["description"]?>
					</p>
					<br>
					<hr style="border: 1px solid #1d9bf0">
					<br>
				</div> 
			<?php
					}
				} else {
			?>
				<h1>0 blogs</h1>
			<?php
				}
			?>

		</div>
	</div>
	<div class="page-info">
		<div class="user-profile">
			<?php
				if(isset($user["nickname"]) && isset($_SESSION["nickname"]) && $user["nickname"] == $_SESSION["nickname"]) {
					if(isset($user["ava"])) { ?>
					<img class="user-profile--ava" src="<?=$user["ava"]?>" alt="">
				<?php } ?>
					<h1><?=$user["full_name"]?></h1>
					<h2><?=$user["about"]?></h2>
					<p><?=mysqli_num_rows($blogs)?> постов за все время</p>
					<a href="<?=$BASE_URL?>/edit-profile.php" class="button">Редактировать</a>
					<a href="<?=$BASE_URL?>/api/user/signout.php" class="button button-danger"> Выход</a>
				<?php
				} else if(!isset($_SESSION["nickname"])){
					if($user["ava"] != "") {
				?>
					<img class="user-profile--ava" src="<?=$user["ava"]?>" alt="">
				<?php
					} else {
				?>
					<img class="user-profile--ava" src="images/avatars/base_avatar.png" alt="">
				<?php
					}
				?>
				
					<h1><?=$user["full_name"]?></h1>
					<h2><?=$user["about"]?></h2>
					<p><?=mysqli_num_rows($blogs)?> постов за все время</p>

				<p>
					Зарегестрируйтесь, чтобы также опубликовывать блоги: <br><br>
					<a class="profile_button" href="<?=$BASE_URL?>/register.php"> Зарегистрироваться</a>
				</p>
			<?php
				} else {
					if($user["ava"] != "") {
				?>
					<img class="user-profile--ava" src="<?=$user["ava"]?>" alt="">
				<?php
					} else {
				?>
					<img class="user-profile--ava" src="images/avatars/base_avatar.png" alt="">
				<?php
					}
				?>				
					<h1><?=$user["full_name"]?></h1>
					<h2><?=$user["about"]?></h2>
					<p><?=mysqli_num_rows($blogs)?> постов за все время</p>
			<?php
				}
			?>
		</div>
	</div>
</section>	
</body>
</html>