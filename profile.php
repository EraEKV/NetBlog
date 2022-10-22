<?php 
	include "config/base_url.php"; 
	include "config/db.php"; 
	include "common/time.php"; 

	if(isset($_GET["nickname"])) {
		
	} else {
		header("Location:$BASE_URL/index.php?page=1");
	}

	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Профиль</title>
	<?php include "views/head.php"; ?>
</head>
<body data-baseurl="<?=$BASE_URL?>"	
>

<?php include "views/header.php"; ?>

<section class="container page">
	<?php
	if(isset($_GET["nickname"])) {
		$user_prep = mysqli_prepare($con, "SELECT * FROM users WHERE nickname=?");
		mysqli_stmt_bind_param($user_prep, "s", $_GET["nickname"]);
		mysqli_stmt_execute($user_prep);
		$user_info = mysqli_stmt_get_result($user_prep);
		$user = mysqli_fetch_assoc($user_info);
	}
	?>
	<div class="page-info adaptive">
		<div class="user-profile adaptive">
			<?php
				if(isset($user["nickname"], $_SESSION["nickname"])) {
					if($user["nickname"] == $_GET["nickname"]) {
			?>
				<div class="user--info adaptive">
					<img class="user-profile--ava" src="<?=$user["ava"]?>" alt="">
					<h1 class="user-full_name"><?=$user["full_name"]?></h1>
					<p class="posts-desc"> </p>
				</div>
				<p class="posts-desc adaptive"> </p>
				<h2><?=$user["about"]?></h2>
				<div class="user--buttons">
					<a href="<?=$BASE_URL?>/edit-profile.php" class="button">Редактировать</a>
					<a href="<?=$BASE_URL?>/api/user/signout.php" class="button button-danger"> Выход</a>
				</div>	
				<?php
				} }  else if(!isset($_SESSION["nickname"])) { 
				?>
					<div class="user--info adaptive">
						<img class="user-profile--ava" src="<?=$user["ava"]?>" alt="">
						<h1 class="user-full_name"><?=$user["full_name"]?></h1>
						<p class="posts-desc"> </p>
					</div>
					<p class="posts-desc adaptive"> </p>
					<h2><?=$user["about"]?></h2>
				<p class="log-btns">
					Зарегестрируйтесь или войдите, чтобы также опубликовывать блоги. <br>
				</p>
			<?php
				} else {
				?>
					<div class="user--info adaptive">
						<img class="user-profile--ava" src="<?=$user["ava"]?>" alt="">
						<h1 class="user-full_name"><?=$user["full_name"]?></h1>
						<p class="posts-desc"> </p>
					</div>
					<p class="posts-desc adaptive"> </p>
					<h2><?=$user["about"]?></h2>
			<?php
				}
			?>
		</div>
	</div>

	<div class="page-content">
		<div class="page-header">
			<?php
				if(isset($user["nickname"]) && isset($_SESSION["nickname"]) && $_SESSION["nickname"] == $_GET["nickname"]) {
			?>
				<h2>Мои блоги</h2>
				<a class="button profile-button" href="newblog.php">Новый блог</a>
			<?php
				} else {
			?>
				<h2>Блоги <?=$_GET["nickname"]?></h2>
			<?php
				}
			?>
		</div>

		<div class="blogs">
			<div id="scrollTop" class="scrollTop ">
				<img class="arrow" src="images/arrow-up.svg">
			</div>
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
					<p class="posts-desc"> </p>
					<a href="<?=$BASE_URL?>/edit-profile.php" class="button profile-button">Редактировать</a>
					<a href="<?=$BASE_URL?>/api/user/signout.php" class="button button-danger profile-button"> Выход</a>
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
					<p class="posts-desc"></p>

				<p>
					Зарегестрируйтесь или войдите, чтобы также опубликовывать блоги. <br>
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
					<p class="posts-desc"></p>
			<?php
				}
			?>
		</div>
	</div>
</section>
	<script src="js/scrollToTop.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
	<script src="js/profile.js"></script>
	<script src="js/modal.js"></script>
</body>
</html>