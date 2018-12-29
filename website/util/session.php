<?php
   include('configurations.php');
   session_start();
   
   $logged_in = false;
   if(isset($_SESSION['session_username']) && isset($_SESSION['session_fullname'])) {
      $logged_in = true;
      $current_username = $_SESSION['session_username'];
      $current_fullname = $_SESSION['session_fullname'];
   }
   
?>
