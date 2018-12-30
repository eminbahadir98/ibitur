<?php
  include('util/session.php');
  include('util/visuals.php');
?>

<html>
  <head>
    <title>IBITUR - Manage Tour</title>
    <link rel="stylesheet" href="style/style.css"/>
    <link rel="stylesheet" href="lib/bootstrap.min.css"/>
  </head>

  <body class="content">

    <?php
      if ($logged_in) {
        echo get_header($current_fullname);
      } else {
        header("location: login.php");
      }

      $tour_found = true;
      if (isset($_GET['id'])) {
        $tour_id = $_GET['id'];
        $tour_preview_query = "SELECT * FROM TourPreview WHERE tour_ID = '1234567890'";
        
      } else {
        $tour_found = false;
      }

    ?>
    
    <div class="inner-content">
      <h1>Manage Tour</h1>
      <hr>
      <h2> Reservations </h2>
    </div>

    <?php
      echo get_footer();
    ?>

  </body>

</html>
