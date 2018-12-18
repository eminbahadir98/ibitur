<?php
   include('configurations.php');
   session_start();
   
   $logged_in = false;
   if(isset($_SESSION['session_sid'])) {
      $logged_in = true;
      $current_sid = $_SESSION['session_sid'];
      $current_sname_query = "SELECT sname FROM student WHERE sid = '$current_sid'";
      $sname_result = mysqli_query($db, $current_sname_query);
      if (mysqli_num_rows($sname_result) == 1) {
         $row = $sname_result->fetch_assoc();
         $current_sname = $row["sname"];
      } else {
         header("location: login.php");
      }
   }
   
?>
