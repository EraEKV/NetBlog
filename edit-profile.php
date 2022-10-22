<?php include "config/base_url.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Редактирование профиля</title>
	<?php include "views/head.php"; ?>
</head>
<body>
	<section class="container page">
		<div class="auth-form">
            <?php 
                $id = $_SESSION["user_id"];
                $user_query = mysqli_query($con, "SELECT * FROM users WHERE id=$id");
                $user = mysqli_fetch_assoc($user_query);
            ?>
            <h1>Редактирование профиля</h1>
			<form class="form" action="<?= $BASE_URL?>/api/user/update.php?nickname=<?=$_SESSION["nickname"]?>" method="POST">
                <fieldset class="fieldset">
                    <input class="input" type="text" name="email" value="<?=$user["email"]?>" placeholder="Введите email">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="text" name="full_name" value="<?=$user["full_name"]?>" placeholder="Полное имя">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="text" name="nickname" value="<?=$user["nickname"]?>" placeholder="Nickname">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input pass1" type="password" name="password" placeholder="Введите старый пароль">
                    <img class="eye-btn eye1" src="" alt="">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input pass2" type="password" name="new_password"  placeholder="Введите новый пароль">
                    <img class="eye-btn eye2" src="" alt="">
                </fieldset>
                <fieldset>
                    <textarea class="input input-textarea" name="about" cols="51" rows="10" placeholder="Кратко напишите о себе"></textarea>
                </fieldset>

                <fieldset class="fieldset">
                    <button class="button" type="submit">Сохранить изменения</button>
                </fieldset>
			</form>
            <fieldset class="fieldset last-btn">
                <a href="<?=$BASE_URL?>/profile.php?nickname=<?=$_SESSION["nickname"]?>">
                    <button class="button-danger button" type="submit">Не сохранять изменения</button>
                </a>
            </fieldset>
		</div>
	</section>

    <script src="js/eye.js"></script>
</body>
</html>