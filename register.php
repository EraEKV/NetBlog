<?php include "config/base_url.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Регистрация в систему</title>
	<?php include "views/head.php"; ?>
</head>
<body>
	
<?php include "views/header.php" ?>
    

	<section class="container page">
		<div class="auth-form">
            <h1>Регистрация</h1>
			<form class="form" action="<?= $BASE_URL?>/api/user/signup.php" method="POST"  enctype="multipart/form-data">
                <fieldset class="fieldset">
                    <button class="button button-yellow input-file">
                        <input class="file_upload" type="file" name="image" placeholder="Выберите картинку для профиля">	
                        Выберите картинку для профиля
                    </button>
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="text" name="email" placeholder="Введите email" required>
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="text" name="full_name" placeholder="Полное имя" required>
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="text" name="nickname" placeholder="Nickname" required>
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="password" name="password" placeholder="Введите пароль" required>
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="password" name="password2" placeholder="Подтвердить пароль" required>
                </fieldset>
                

                <fieldset class="fieldset">
                    <button class="button" type="submit">Зарегистрироваться</button>
                </fieldset>
			</form>
		</div>
	</section>
    <!-- <?php echo $_FILES["image"]["name"]; ?> -->
</body>
</html>