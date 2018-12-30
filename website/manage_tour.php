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
      if ($logged_in && $current_is_staff) {
        echo get_header($current_fullname);
      } else {
        header("location: login.php");
      }

      $tour_found = true;
      if (isset($_GET['id'])) {
        $tour_id = $_GET['id'];
        $tour_preview_query = "SELECT * FROM TourPreview WHERE tour_ID = $tour_id";
        $tour_preview_result = mysqli_query($db, $tour_preview_query);
        if ($tour_preview_result && mysqli_num_rows($tour_preview_result) == 1) {
          $result_row = $tour_preview_result->fetch_assoc();
        } else {
          $tour_found = false;
        }
      } else {
        $tour_found = false;
      }

    ?>
    
    <div class="inner-content">
      <h1>Manage Tour</h1>
      <hr>
      <?php
        if (!$tour_found) {
          echo "<h2>The tour is not found.<h2>";
        } else {
          echo "<h2>MyTourName</h2>";
        }
      ?>
    </div>

    <?php
      echo get_footer();
    ?>

  </body>

</html>
