<?php
   define('SERVER_ADDRESS', 'localhost:3306');
   define('USERNAME', 'root');
   define('PASSWORD', 'root');
   define('DATABASE_NAME', 'ibitur_db');
   $db = mysqli_connect(SERVER_ADDRESS,USERNAME,PASSWORD,DATABASE_NAME);
?>
