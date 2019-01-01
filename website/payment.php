<?php
  include('util/session.php');
  include('util/visuals.php');

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  if(isset($_POST['payment-submit'])) {
    $rez_id = $_POST['rez_id'];
    $tour_id = $_POST['tour_id'];
    $payment_query = "update Reservation set payment_status = 'PAID' where ID = '$rez_id';";
    $payment_result = mysqli_query($db, $payment_query);

    if($payment_result) {
      echo "<script>alert('Payment complete.');";
      echo "window.location.href = 'view_tour.php?id=$tour_id';</script>";
    }
    else {
      echo "<script>alert('Payment failure.')</script>";
    }
  }
  $tour_found = true;
  $tour_is_cancelled = false;
  $tour_cancel_reason = "";

  if (isset($_POST['tour_id'])) {
    $tour_id = $_POST['tour_id'];
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

  $tour_card = get_tour_summary_card($tour_id, $tour_name, $tour_image_path, $tour_start_date, $tour_end_date, $tour_description,
      $tour_price, $tour_remaining_quota);
  
  $rez_id = $_POST['rez_id'];

?>



<html>

  <head>
    <title>IBITUR - Tour Details</title>
    <link rel="stylesheet" href="style/style.css"/>
    <link rel="stylesheet" href="lib/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  </head>

  <script>
    $(document).ready(function() {
      var init_price = parseFloat($("#final_price").text());

      // checks and applies bonus points discount.
      $(".bonus_box").change(function() {
          var checked = $(this).is(':checked');
          $(".bonux_box").prop('checked',false);
          var discount;
          var final_price;
          discount = parseFloat($(this).val());
          if(checked) {
              $(this).prop('checked',true);
              
              final_price = init_price - discount;
              init_price = init_price - discount;
          }
          else {
              init_price = init_price + discount;
              final_price = init_price;
          }
          
          $("#final_price").text(final_price);
      });

      // checks and applies promotion card discount.
      $(".promo_boxes").change(function() {
          var checked = $(this).is(':checked');
          $(".promo_boxes").prop('checked',false);
          var discount;
          var final_price;
          if(checked) {
              $(this).prop('checked',true);
              discount = parseFloat($(this).val());
              final_price = init_price - init_price*discount/100.0;
          }
          else {
              final_price = init_price;
          }
          $(".bonus_box").prop("disabled", checked);

          $("#final_price").text(final_price);
      });
    });
    
  </script>
  <body class="content">

    <?php
      if ($logged_in) {
        echo get_header($current_fullname, $current_is_staff);
      } else {
        echo get_header(null, false);
      }

      echo "<h1>Tour Payment</h1>";
      echo $tour_card;
      echo "<hr>";
    ?>
    
    <div id="payment-settings">
    <h3> Booking Points </h3>
    <hr>
      <?php
        $booking_pts_query = "select booking_points from CustomerAccount where ID='$current_id';";
        $booking_pts_result = mysqli_query($db, $booking_pts_query);

        $booking_pts = $booking_pts_result->fetch_assoc()['booking_points'];
        echo "<label>Booking Points: </label><span id='b_pts'>$booking_pts</span><br>";
        echo "<label>Use Points: </label><input type='checkbox' class='bonus_box' value='$booking_pts'/>";
      ?>
    <h3> Promotion Cards </h3>
      <hr>
      <?php
          $promotion_query = "select * from PromotionCard natural join CustomerPromotionCards where customer_ID='$current_id';";
          $promotion_result = mysqli_query($db, $promotion_query);

          if($promotion_result->num_rows == 0) {
              echo "<p>You don't have any promotion cards yet.</p>";
          }
          else {
              echo "<table class='table table-bordered'>
              <tr>
                  <th> Promotion Code </th>
                  <th> Discount Percent </th>
                  <th> Use </th>
              </tr>";
              while($row = $promotion_result->fetch_assoc()) {
                  $code = $row['promo_code'];
                  $discount_per = $row['discount_percent'];
                  echo "<tr>";
                  echo "<td>" . $code . "</td>";
                  echo "<td>" . $discount_per . "</td>";
                  echo "<td>" . "<input type='checkbox' class='promo_boxes' id='$code' name='promo' value='$discount_per'>" . "</td>";
                  echo "</tr>";
              }

              echo "</table>";
          }

      ?>
    </div>

    <div id="payment-amount">
            <label>Final Price: </label><span id="final_price"><?php echo $tour_price;?></span> TRY
    </div>

    <form action='' method ='post'>
    <input type='hidden' name='tour_id' value=<?php echo "'$tour_id'";?> />
      <input type='hidden' name='rez_id' value=<?php echo "'$rez_id'";?> />
      <button class="right btn" type="submit" name="payment-submit">Complete Payment</button>
    </form>
    
    <?php
      echo get_footer();
    ?>

  </body>


</html>