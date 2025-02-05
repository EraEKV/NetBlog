<?php
    require_once 'env_loader.php';

    loadEnv(__DIR__ . '/../.env');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
   
    date_default_timezone_set('Asia/Almaty');

    // $con - это само подключение к БД(базе данных)
    $con = mysqli_connect(
        getenv('DB_HOST'), 
        getenv('DB_USER'), 
        getenv('DB_PASS'), 
        getenv('DB_NAME'), 
        getenv('DB_PORT')
    );
    
    if(mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: ".mysqli_connect_error();
        echo getenv('DB_HOST');
        exit();
    }
    mysqli_set_charset($con, "utf8");
?>
