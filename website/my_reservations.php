<?php
   include('util/session.php');
   include('util/visuals.php');

   function getReservations($reservation_query) {
      $db = $GLOBALS["db"];
      $reservations_result = mysqli_query($db, $reservation_query);

        $reservations = "";
        while ($row = $reservations_result->fetch_assoc()) {
            $tour_ID = $row["tour_ID"];
            $tour_name = $row["name"];
            $tour_description = $row["description"];
            $tour_image_path = $row["image_path"];
            $tour_start_date = $row["start_date"];
            $tour_end_date = $row["end_date"];

            $reservations .= "
                <div>
                    $tour_name<br>
                    $tour_image_path <br>
                    <a href='view_tour.php?id=$tour_ID'>Details</a> <br><br>
                    Start: $tour_start_date <br>
                    End: $tour_end_date <br><br>
                    $tour_description <br>
                    <br>
                </div>
            ";
        }
        
        if ($reservations == "") {
            $reservations = "No reservations...<br><br>";
        }

        return $reservations;
  }

  function getActiveReservations() {
      $current_id = $GLOBALS["current_id"];
      $active_reservation_query = "
          SELECT tour_ID, name, description, image_path, start_date, end_date
          FROM (TourPreview NATURAL JOIN Reservation)
          WHERE Reservation.customer_ID = $current_id
          AND Reservation.cancel_date IS NULL
          AND start_date > NOW();";
      return getReservations($active_reservation_query);
  }

  function getCancelledReservations() {
      $current_id = $GLOBALS["current_id"];
      $cancelled_reservation_query = "
          SELECT tour_ID, name, description, image_path, start_date, end_date
          FROM (TourPreview NATURAL JOIN Reservation)
          WHERE Reservation.customer_ID = $current_id
          AND Reservation.cancel_date IS NOT NULL
          AND start_date > NOW();";
      return getReservations($cancelled_reservation_query);
  }

  function getPastReservations() {
      $current_id = $GLOBALS["current_id"];
      $past_reservation_query = "
          SELECT tour_ID, name, description, image_path, start_date, end_date
          FROM (TourPreview NATURAL JOIN Reservation)
          WHERE Reservation.customer_ID = $current_id
          AND Reservation.cancel_date IS NULL
          AND end_date < NOW();";
      return getReservations($past_reservation_query);
  }

?>

<html>
   
   <head>
      <title>IBITUR - My Reservations</title>
      <link rel="stylesheet" href="style/style.css"/>
      <link rel="stylesheet" href="lib/bootstrap.min.css"/>
   </head>
   
   <body class="content">

      <?php
         if ($logged_in) {
            echo get_header($current_fullname, $current_is_staff);
         } else {
            header("location: login.php");
         }
      ?>

      <div class="inner-content">

      <h1>My Reservations</h1>
      <hr>

      <h3> Active Reservations </h3>
      <div>
            <?php
               echo getActiveReservations();
            ?>
      </div>
      <hr>

      <h3> Cancelled Reservations </h3>
      <div>
            <?php
               echo getCancelledReservations();
            ?>
      </div>
      <hr>

      <h3> Past Reservations </h3>
      <div>
            <?php
               echo getPastReservations();
            ?>
      </div>
      <hr>
      <br><br>

      </div>

      <?php
         echo get_footer();
      ?>

   </body>
   
</html>

