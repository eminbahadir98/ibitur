<?php
  include('util/session.php');
  include('util/visuals.php');
?>

<html>
  <head>
    <title>IBITUR - Tour Details</title>
    <link rel="stylesheet" href="style/style.css"/>
    <link rel="stylesheet" href="lib/bootstrap.min.css"/>
  </head>

  <body class="content">

    <?php
      if ($logged_in) {
        echo get_header($current_fullname);
      } else {
        echo get_header(null);
      }

      $tour_found = true;
      $tour_is_cancelled = false;
      $tour_cancel_reason = "";
      if (isset($_GET['id'])) {
        $tour_id = $_GET['id'];
        $tour_preview_query = "SELECT * FROM TourPreview WHERE tour_ID = $tour_id";
        $tour_preview_result = mysqli_query($db, $tour_preview_query);
        if ($tour_preview_result && mysqli_num_rows($tour_preview_result) == 1) {
          $result_row = $tour_preview_result->fetch_assoc();
          $tour_name = $result_row["name"];
          $tour_description = $result_row["description"];
          $tour_image_path = $result_row["image_path"];
          $tour_price = $result_row["price"];
          $tour_start_date = $result_row["start_date"];
          $tour_end_date = $result_row["end_date"];
        } else {
          $tour_found = false;
        }
      } else {
        $tour_found = false;
      }

    ?>
    
    <div class="inner-content">
      <h1>Tour Details</h1>
      <hr>
      <?php
        if (!$tour_found) {
          echo "<h2>The tour is not found.<h2>";
        } else {
          $cancel_indicator = $tour_is_cancelled ? "[CANCELLED]" : "";
          echo "
            <h2>$tour_name $cancel_indicator</h2>
            <div>
              $tour_image_path <br><br>
              Start: $tour_start_date <br>
              End: $tour_end_date <br><br>
              $tour_description <br>
              <br>
              $tour_price TL
            </div>
            <hr>
          ";
        }
      ?>
    </div>

  </body>

</html>
