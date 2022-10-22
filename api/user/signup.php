<?php
    include "../../config./base_url.php";
    include "../../config./db.php";

    // isset и strlen - это валидация(проверка данных)
    if(isset($_POST["email"], $_POST["nickname"], $_POST["full_name"], $_POST["password"], $_POST["password2"]) 
    && strlen($_POST["email"]) > 0
    && strlen($_POST["nickname"]) > 0
    && strlen($_POST["full_name"]) > 0
    && strlen($_POST["password"]) > 0
    && strlen($_POST["password2"]) > 0
    ) {
        $email = $_POST["email"];           // Создал переменные для каждого, чтобы терять меньше времени
        $nickname = $_POST["nickname"];
        $full_name = $_POST["full_name"];
        $password = $_POST["password"];
        $password2 = $_POST["password2"];

        if($password != $password2) {
            header("Location: $BASE_URL/register.php?error=1");
            exit();     // типа выйти из функции
        }

        // if(strlen($password) <= 8 && strlen($password2) <= 8  && $password == $password2) {
        //     header("Location: $BASE_URL/register.php?error=4");
        //     exit();
        // }

        // SELECT - это запрос с базы данных, чтобы получить из неё данные
        // Синтаксис: SELECT *(всё)/column  имя_таблицы  
        $prep = mysqli_prepare($con, "SELECT * FROM users WHERE email=? OR nickname=?");        // Проверяем есть ли уже такой пользователь в базе данных, если нет, то регестрируем ниже по коду
        mysqli_stmt_bind_param($prep, "ss", $email, $nickname);     // Заполняем ? в прошлом коде, потому что БД ждёт эту функцию, чтобы заполнить их
        mysqli_stmt_execute($prep);         // Чтобы два запроса объединить тем самым БД их правильно принимает 
        $user = mysqli_stmt_get_result($prep);      // Переменная user хранит массив из одного объекта

        if(mysqli_num_rows($user) > 0) {        // num_rows - это чтобы понять длину массива
            header("Location: $BASE_URL/login.php?error=2");
            exit();
        }

        $hash1 = sha1($password);        // sha1 - это хэширование данных!
        
        if(isset($_FILES["image"], $_FILES["image"]["name"])) {
            $ext = end(explode(".", $_FILES["image"]["name"]));
            $image_name = time().".".$ext;
            move_uploaded_file($_FILES["image"]["tmp_name"], "../../images/avatars/$image_name");
            $path = "images/avatars/".$image_name;
            if($ext == "") {
                $path = "images/avatars/base_avatar.png";
            }
        } else {
            $path = "images/avatars/base_avatar.png";
        }
        
        // INSERT - это запрос с базы данных, чтобы занести в неё данные
        // Синтаксис: INSERT INTO имя_таблицы () VALUES (значения) 
        $prep1 = mysqli_prepare($con, 
        "INSERT INTO users (email, nickname, full_name, password, ava) 
        VALUES(?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($prep1, "sssss", $email, $nickname, $full_name, $hash1, $path);
        mysqli_stmt_execute($prep1);

        header("Location: $BASE_URL/index.php");
    } else {
        header("Location: $BASE_URL/register.php?error=3");
    }
?>
 