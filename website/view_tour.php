<?php
  include('util/session.php');
  include('util/visuals.php');

  $reservation_cancel_succeed = false;
  $reservation_cancel_performed = false;
  $reservation_performed = false;
  $reservation_succeed = false;

  if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['cancel-reservation'])) {
      $cancelled_tour_id = $_GET['id'];
      $cancel_query = "UPDATE Reservation SET cancel_date = NOW()
        WHERE customer_ID = $current_id AND tour_ID = $cancelled_tour_id";
      $reservation_cancel_succeed = mysqli_query($db, $cancel_query);
      $reservation_cancel_performed = true;
    }

    else if(isset($_POST['reserve-submit'])) {
      $tour_id = $_GET['id'];
      
      $re_reservation_check_query = "SELECT ID FROM Reservation WHERE customer_ID = $current_id AND tour_ID = $tour_id;";
      $re_reservation_check_result = mysqli_query($db, $re_reservation_check_query);
      $re_reservation_needed = mysqli_num_rows($re_reservation_check_result) > 0;
      
      if ($re_reservation_needed) {
        $old_reservation_id = $re_reservation_check_result->fetch_assoc()["ID"];

        $remove_old_dependents_query = "DELETE FROM IncludedDependents WHERE reservation_ID = $old_reservation_id;";
        $res1 = mysqli_query($db, $remove_old_dependents_query);

        $remove_old_reservation_query = "DELETE FROM Reservation WHERE ID = $old_reservation_id";
        $res2 = mysqli_query($db, $remove_old_reservation_query);
      }
      
      $reservation_query = "INSERT INTO Reservation(customer_ID, tour_ID, issue_date,
          payment_status, cancel_date) VALUES($current_id, $tour_id, NOW(), 'UNPAID', NULL);";

      $reservation_succeed = mysqli_query($db, $reservation_query);
      
      if($reservation_succeed) {
        $reservation_id_query = "select ID from reservation where customer_ID = $current_id and tour_ID=$tour_id;";
        $reservation_id_result = mysqli_query($db, $reservation_id_query);
        $reservation_id_data = $reservation_id_result->fetch_assoc();
        
        if($reservation_id_result->num_rows == 1) {
          $rez_id = $reservation_id_data['ID'];
        }
        else {
          $rez_id = -1;
        }
      }
      
      if($rez_id != -1) {
        $checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();
        foreach($checkboxes as $value) {
          $ins_dep_query = "insert into IncludedDependents(reservation_ID, dependent_ID) values($rez_id, $value);";
          $ins_dep_result = mysqli_query($db, $ins_dep_query);
        }
      }
      $reservation_performed = true;
    }
    
  }

  $payment_performed = !$reservation_cancel_performed && isset($_GET['paid']);
  if ($payment_performed) {
    $payment_succeed = ($_GET['paid'] == "true");
  }
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
        echo get_header($current_fullname, $current_is_staff);
      } else {
        echo get_header(null, false);
      }

      $reservation_cancel_alert = "";
      if ($reservation_cancel_performed) {
        $reservation_cancel_message = $reservation_cancel_succeed ?
          "Your reservation has been successfully cancelled." :
          "Cancellation failed. Please try again later.";
        $reservation_cancel_alert =
          "<div class='alert alert-success' role='alert'>
            $reservation_cancel_message
          </div>";
      }

      $reservation_alert = "";
      if ($reservation_performed) {
        $reservation_message = $reservation_succeed ?
          "You have successfully made reservation to this tour." :
          "Reservation failed. Please try again later.";
        $reservation_alert =
          "<div class='alert alert-success' role='alert'>
            $reservation_message
          </div>";
      }

      $payment_alert = "";
      if ($payment_performed) {
        $payment_message = $payment_succeed ?
          "Payment completed." :
          "Payment failed. Please try again later.";
        $payment_alert =
          "<div class='alert alert-success' role='alert'>
            $payment_message
          </div>";
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
          $tour_remaining_quota = $result_row["remaining_quota"];

          $cancel_check_query = "SELECT * FROM TourCancel WHERE tour_ID='$tour_id'";
          $cancel_check_result = mysqli_query($db, $cancel_check_query);
          if (mysqli_num_rows($cancel_check_result) > 0) {
            $tour_is_cancelled = true;
            $tour_cancel_reason = ($cancel_check_result->fetch_assoc())["cancel_reason"];
          }
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
        echo $reservation_cancel_alert;
        echo $reservation_alert;
        echo $payment_alert;

        if (!$tour_found) {
          echo "<h2>The tour is not found.<h2>";
        } else {
          $cancel_indicator = $tour_is_cancelled ? " [TOUR CANCELLED]" : "";

          $tour_days = "";
          $days_query = "SELECT day_no, day_date, D.description AS description
            FROM Tour T, TourDay D WHERE T.ID = $tour_id AND T.ID = D.tour_ID ORDER BY day_date";
          $days_result = mysqli_query($db, $days_query);
          while ($row = $days_result->fetch_assoc()) {
            $day_no = $row["day_no"];
            $day_date = $row["day_date"];
            $description = $row["description"];
            $tour_days .= "
              <div>
                Day $day_no ($day_date)<br>
                $description
              </div>
            ";
          }
          if ($tour_days == "") {
            $tour_days = "No tour day entry...";
          }

          $tour_accommodations = "";
          $accommodations_query = "SELECT name, enter_date, exit_date, star_rating
            FROM Accommodation, Hotel WHERE Accommodation.tour_ID = $tour_id
            AND Hotel.ID = Accommodation.place_ID;";
          $accommodations_result = mysqli_query($db, $accommodations_query);
          while ($row = $accommodations_result->fetch_assoc()) {
            $name = $row["name"];
            $enter_date = $row["enter_date"];
            $exit_date = $row["exit_date"];
            $star_rating = $row["star_rating"];
            $tour_accommodations .= "
              <div>
                <b>$name</b> (from $enter_date to $exit_date)</b><br>
                Star Rating: $star_rating
              </div>
            ";
          }
          if ($tour_accommodations == "") {
            $tour_accommodations = "No accommodation entry...";
          }
          
          $tour_travel_routes = "";
          $tour_travel_routes_query = "SELECT vehicle_type, company_name,
            arriv_time, arriv_address, dept_time, dept_address FROM TravelRoute
            WHERE tour_ID = $tour_id;";
          $tour_travel_routes_result = mysqli_query($db, $tour_travel_routes_query);
          while ($row = $tour_travel_routes_result->fetch_assoc()) {
            $vehicle_type = $row["vehicle_type"];
            $company_name = $row["company_name"];
            $arriv_time = $row["arriv_time"];
            $arriv_address = $row["arriv_address"];
            $dept_time = $row["dept_time"];
            $dept_address = $row["dept_address"];
            $tour_travel_routes .= "
              <div>
                A <b>$vehicle_type</b> of <b>$company_name</b><br>
                From $dept_address ($dept_time) <br>
                To $arriv_address ($arriv_time). <br>
              </div>
            ";
          }
          if ($tour_travel_routes == "") {
            $tour_travel_routes = "No trip event entry...";
          }

          $tour_trip_events = "";
          $trip_events_query = "SELECT TripEvent.name AS name, description,
            City.name AS city_name, trip_date FROM TripEvent, City
            WHERE TripEvent.tour_ID = $tour_id AND City.ID = TripEvent.city_ID;";
          $trip_events_result = mysqli_query($db, $trip_events_query);
          while ($row = $trip_events_result->fetch_assoc()) {
            $name = $row["name"];
            $description = $row["description"];
            $city_name = $row["city_name"];
            $trip_date = $row["trip_date"];
            $tour_trip_events .= "
              <div>
                <b>$name</b> in <b>$city_name ($trip_date)</b><br>
                $description
              </div>
            ";
          }
          if ($tour_trip_events == "") {
            $tour_trip_events = "No trip event entry...";
          }

          $has_reservation_text = "";

          $tour_action = "";
          if ($tour_remaining_quota > 0) {
            $tour_action = "
            <form class='tour-action-button' action='reserve_tour.php' method='GET'>
              <input type='hidden' name='id' value='$tour_id'> 
              <input type='submit' class='btn btn-primary' value='Make Reservation'>
            </form>";
          }

          $payment_action = "";
          
          if ($logged_in && $current_is_staff) {
            $tour_action = "
              <form action='manage_tour.php' method='GET'>
                <input type='hidden' name='id' value='$tour_id'> 
                <input type='submit' class='btn btn-primary' value='Manage Tour'>
              </form>";
          }

          if ($logged_in && !$current_is_staff) {
            $reserved_check_query = "SELECT ID, cancel_date, payment_status FROM Reservation WHERE customer_ID = $current_id AND tour_ID = $tour_id";
            $reserved_check_result = mysqli_query($db, $reserved_check_query);
            if (mysqli_num_rows($reserved_check_result) > 0) {
                $row = $reserved_check_result->fetch_assoc();
                if ($row["cancel_date"] == null) {
                  $has_reservation_text = "You have reservation for this tour.";
                  $tour_action = "
                   <div class='tour-action-button'>
                      <form class='tour-action-button' action='' method='POST'>
                        <input type='hidden' name='id' value='$tour_id'> 
                        <input type='submit' class='btn btn-danger' name='cancel-reservation' value='Cancel Reservation'>
                      </form>
                    </div>
                  ";

                  $payment_action = "<button class='btn' disabled>Paid</button>";
                  if ($row["payment_status"] != "PAID") {
                    $rez_id = $row["ID"];
                    $payment_action = "
                      <div class='tour-action-button'>
                        <form class='tour-action-button' action='payment.php' method='post'>
                          <input type='hidden' name='rez_id' value=$rez_id /> 
                          <input type='hidden' name='tour_id' value='$tour_id' />
                          <input class='btn btn-primary' type='submit' name='submit' value='Make payment'/>
                        </form>
                      </div>
                    ";
                  }
                }
            }
          }
          
          $quota_display = $tour_remaining_quota > 0 ?
            "$tour_remaining_quota spots remaining."
            : "The quota for this tour is full.";

          $tour_card = get_tour_details_card($tour_action, $payment_action,
            $tour_id, $tour_name . $cancel_indicator, $tour_image_path, $tour_start_date,
            $tour_end_date,
            $tour_description,
            $tour_price, $tour_remaining_quota);
          
          echo "
            $tour_card
            <hr>
            <br>

            <h4>Tour Schedule</h4>
            $tour_days
            <hr>
            <br>

            <h4>Accommodations</h4>
            $tour_accommodations
            <hr>
            <br>

            <h4>Travel Routes</h4>
            $tour_travel_routes
            <hr>
            <br>

            <h4>Trip Events</h4>
            $tour_trip_events
            <hr>
            <br>

          ";
        }
      ?>
    </div>

    <?php
      echo get_footer();
    ?>

  </body>

</html>
