<?php
   include('util/session.php');
   include('util/visuals.php');
?>

<html>
   
   <head>
      <title>IBITUR - Home</title>
      <link rel="stylesheet" href="style/style.css"/>
      <link rel="stylesheet" href="lib/bootstrap.min.css"/>
   </head>
   
   <body class="content">
      
      <?php
         if ($logged_in) {
            echo get_header(true, "Bahadir");
         } else {
            echo get_header(false, "");
         }
      ?>

      <h1 class="home-title">IBITUR - Tour Reservation System</h1>
      <div class="home-content">
         <h2>Where do you want to travel?</h2>
         <input type="text" name="username"/>
         <input class=" btn" type="submit" value="Search"/>
      </div>

      <?php
         echo get_footer();
      ?>

   </body>
   
</html>
