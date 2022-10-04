<?php include "config/base_url.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Войти в систему</title>
	<?php include "views/head.php"; ?>
</head>
<body>

    <?php include "views/header.php" ?>

	<section class="container page">
		<div class="auth-form">
            <h1>Вход</h1>
			<form class="form" method="POST" action="<?=$BASE_URL?>/api/user/signin.php">
                <fieldset class="fieldset">
                    <input class="input" type="text" name="email" placeholder="Введите email" required>
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" id="pass1" type="password" name="password" placeholder="Введите пароль" required>
                    <img class="eye-btn" id="eye1" src="" alt="">
                </fieldset>
                <fieldset class="fieldset" style="display: none;">
                    <input class="input" id="pass2" type="password" placeholder="Введите пароль">
                    <img class="eye-btn" id="eye2" src="" alt="">
                </fieldset>

                <fieldset class="fieldset">
                    <button class="button" type="submit">Войти</button>
                </fieldset>
			</form>
		</div>
	</section>
    <script src="js/eye.js"></script>
</body>
</html>