<header class="header container">
	<div class="header-logo">
	    <a href="index.php?page=1">Net<span>Blog</span></a>	
	</div>
	<?php 
		if(isset($_GET["id"]) || isset($_GET["nickname"])) {
	?>
	<?php } else { ?>
		<form class="header-search">
			<input type="hidden" name="page" value="1">
			<input type="text" class="input-search" name="q" placeholder="Поиск по блогам">
			<button class="button button-search">
				<img src="images/search.svg" alt="">	
				Найти
			</button>
		</form>
		<?php } ?>
	<div>
		
		<?php
			if($_SESSION != "") {
				$user_prep = mysqli_prepare($con, "SELECT * FROM users WHERE id=?");
				mysqli_stmt_bind_param($user_prep, "s", $_SESSION["user_id"]);
				mysqli_stmt_execute($user_prep);
				$user_info = mysqli_stmt_get_result($user_prep);
				$user_session = mysqli_fetch_assoc($user_info);																			
			}
			
			if(isset($_SESSION["user_id"])) {
		?>
			<a href="profile.php?nickname=<?=$_SESSION["nickname"]?>">
				<img class="avatar" src="<?=$BASE_URL?>/<?=$user_session["ava"]?>" alt="Avatar">
			</a>
		<?php
			} else {
		?>
			<div class="button-group log">
				<a href="<?=$BASE_URL?>/register.php?reg" class="button">Регистрация</a>
				<?php if(!isset($_GET["reg"])) { ?>
					<a onclick="openModalWindow()" class="button button-log">Вход</a>
					<a onclick="openModalWindow()" class="button button-log adaptive"><img src="<?=$BASE_URL?>/images/login.svg"></a>
					<div data-baseurl="<?=$BASE_URL?>" class="modal_window" id="modal_window">
						
						<div class="modal_inner">
							<div class="close" onclick="closeModalWindow()">
								<span>X</span>
							</div>
							<h2>Рады вас видеть!</h2>
							<div class="auth-form">
								<form class="form" method="POST" action="<?=$BASE_URL?>/api/user/signin.php">
									<fieldset class="fieldset">
										<input class="input" type="text" name="email" placeholder="Введите email" required>
									</fieldset>
									<fieldset class="fieldset">
										<input class="input pass1" type="password" name="password" placeholder="Введите пароль" required>
										<img class="eye-btn eye1" src="" alt="">
									</fieldset>
									<fieldset class="fieldset" style="display: none;">
										<input class="input pass2" type="password" placeholder="Введите пароль">
										<img class="eye-btn eye2" src="" alt="">
									</fieldset>

									<fieldset class="fieldset">
										<button class="button" type="submit">Войти</button>
									</fieldset>
								</form>
							</div> 
						</div>
						<div class="modal_background" onclick="closeModalWindow()"></div>
					</div>
				<?php } else { ?>
					<a href="<?=$BASE_URL?>/index.php" class="button button-log">Вход</a>
					<a href="<?=$BASE_URL?>/index.php" class="button button-log adaptive"><img src="<?=$BASE_URL?>/images/login.svg"></a>
				<?php } ?>
				<script src="<?=$BASE_URL?>/js/eye.js"></script>
			</div>
		<?php
			}
		?>
		
	</div>
		
	<script src="js/modal.js"></script>
</header>