<header class="header container">
	<div class="header-logo">
	    <a href="index.php?page=1">Net<span>Blog</span></a>	
	</div>
	<?php 
		if(!isset($_GET["id"]) || !isset($_GET["nickname"])) {
	?>
		<form class="header-search">
			<input type="hidden" name="page" value="1">
			<input type="text" class="input-search" name="q" placeholder="Поиск по блогам">
			<button class="button button-search">
				<img src="images/search.svg" alt="">	
				Найти
			</button>
		</form>
	<?php } else { echo "proverka"; } ?>
	<div>
		
		<?php
			if($_SESSION != "") {
				$user_prep = mysqli_prepare($con, "SELECT * FROM users WHERE id=?");
				mysqli_stmt_bind_param($user_prep, "s", $_SESSION["user_id"]);
				mysqli_stmt_execute($user_prep);
				$user_info = mysqli_stmt_get_result($user_prep);
				$user = mysqli_fetch_assoc($user_info);																			
			}
			
			if(isset($_SESSION["user_id"])) {
		?>
			<a href="profile.php?nickname=<?=$_SESSION["nickname"]?>">
				<img class="avatar" src="<?=$BASE_URL?>/<?=$user["ava"]?>" alt="Avatar">
			</a>
		<?php
			} else {
		?>
			<div class="button-group">
				<a href="<?=$BASE_URL?>/register.php" class="button">Регистрация</a>
				<a href="<?=$BASE_URL?>/login.php" class="button">Вход</a>
			</div>
		<?php
			}
		?>
		
	</div>
</header>