<?php
   include('configurations.php');
   session_start();
   
   $logged_in = false;
   if(isset($_SESSION['session_username'])
      && isset($_SESSION['session_fullname'])
      && isset($_SESSION['session_is_staff'])
      && isset($_SESSION['session_id'])) {
      $logged_in = true;
      $current_username = $_SESSION['session_username'];
      $current_fullname = $_SESSION['session_fullname'];
      $current_is_staff = $_SESSION['session_is_staff'];
      $current_id = $_SESSION['session_id'];
   }
   
?>
