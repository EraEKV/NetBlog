<?php
    require_once 'env_loader.php';

    loadEnv(__DIR__ . '/../.env');

    $BASE_URL = getenv('BASE_URL');
?>