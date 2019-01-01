<?php
  include('util/session.php');
  include('util/visuals.php');

  $cancel_performed = false;
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $cancel_reason_input = mysqli_real_escape_string($db, $_POST['cancel_reason']);
    $cancelled_tour_id = $_GET['id'];
    $cancel_query = "INSERT INTO TourCancel(tour_ID, cancel_date, cancel_reason)
      VALUES($cancelled_tour_id, NOW(), '$cancel_reason_input')";
    $cancel_succeed = mysqli_query($db, $cancel_query);
    $cancel_performed = true;
  }

?>

<html>
  <head>
    <title>IBITUR - Tour Management</title>
    <link rel="stylesheet" href="style/style.css"/>
    <link rel="stylesheet" href="lib/bootstrap.min.css"/>
  </head>

  <body class="content">

    <?php
      if ($logged_in && $current_is_staff) {
        echo get_header($current_fullname, $current_is_staff);
      } else {
        header("location: login.php");
      }

      if ($cancel_performed) {
        $cancel_message = $cancel_succeed ?
          "The tour has been successfully cancelled." :
          "Cancellation failed. The tour might be cancelled already.";
        echo
          "<div class='alert alert-success' role='alert'>
            $cancel_message
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

          $reservations_query = "SELECT Account.ID AS customer_ID, 
            first_name, middle_name, last_name, payment_status, dependent_count
            FROM Reservation, Account, DependentCounts
            WHERE Reservation.tour_ID = $tour_id
            AND Reservation.customer_ID = Account.ID
            AND Reservation.ID = DependentCounts.reservation_ID";
          $reservations_result = mysqli_query($db, $reservations_query);

          $reservation_rows = "";
          while ($row = $reservations_result->fetch_assoc()) {
            $customer_fullname = $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"];
            $has_paid = ($row["payment_status"] == "PAID");
            $reservation_rows .= createReservationRow($row["customer_ID"], $customer_fullname,
              $row["dependent_count"] + 1, $has_paid);
          }
          
          if ($reservation_rows == "") {
            $reservation_rows = "
              <tr>
                <td>&lt;empty&gt;</td>
                <td>&lt;empty&gt;</td>
                <td>&lt;empty&gt;</td>
              </tr>";
          }

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
      <h1>Tour Management</h1>
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

            <h2>Reservations</h2>
            <div>
              <table class='table table-bordered'>
              <thead>
                <tr>
                <th>Name</th>
                <th>Traveler Count</th>
                <th>Paid</th>
                </tr>
              </thead>
              <tbody>
                $reservation_rows
              </tbody>
              </table>
            </div>
            <hr>
          ";
          if ($tour_is_cancelled) {
            echo "
              <h2>Cancel Info</h2>
              <div>
                $tour_cancel_reason
              </div>
            ";
          } else {
            echo "
              <h2>Cancel Tour</h2>
              <div>
                <form name='cancel-form' action='' method='post'>
                  <label>Reason for cancellation:</label>
                  <input required class='form-control' type='text' name='cancel_reason'/><br>
                  <input class='right btn' type='submit' value='Submit Cancellation'/>
                </form>
              </div>
            ";
          }
        }
      ?>
    </div>

    <?php
      function createReservationRow($customer_id, $customer_fullname, $traveler_count, $has_paid) {
        $paid_checked = $has_paid ? "checked" : "";
        return "
          <tr>
            <td><a href='customer_profile.php?id=$customer_id'>$customer_fullname</a></td>
            <td>$traveler_count</td>
            <td class='paid-checkbox'><input class='form-check-input' type='checkbox'
              $paid_checked disabled></td>
          </tr>
        ";
      }
      echo get_footer();
    ?>

  </body>

</html>
