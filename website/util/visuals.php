<?php

  function get_header($username, $is_staff) {
    $part = $is_staff ? "<a href = 'my_tours.php'>My Tours</a>" 
        : "<a href = 'my_reservations.php'>My Reservations</a>";
    if ($username != null) {
      $profile_bar =
        "Welcome <b>$username</b> |
        $part |
        <a href = 'my_account.php'>My Account</a> |
        <a href = 'logout.php'>Logout</a>";
    } else {
      $profile_bar =
        "<a href = 'register.php'>Register</a> |
        <a href = 'login.php'>Login</a>";
    }
    
    return 
    "<div class='header'>
      <a href = 'index.php'>Home</a> |
      <a href = 'tour_list.php'>Tours</a> |
      <a href = 'tour_list.php'>Top Lists</a>
      <span class='right'>
        $profile_bar
      </span>
    </div>
    <hr>";
  }
  
  function get_footer() {
    return 
    "<div class='footer'>
      <hr>
      IBITUR on Social Media:
      <a href = '#'>Facebook</a>,
      <a href = '#'>Twitter</a>
      <div class='right'>
        <a href = '#'>About IBITUR</a> |
        <a href = '#'>Contact us</a>
      </div>
    </div>";
  }

  function format_datetime($datetime) {
    $sd_arr = explode("-", (explode(" ", $datetime)[0]));
    return $sd_arr[2] . "/" . $sd_arr[1] . "/" . $sd_arr[0];
  }

  function get_tour_card($id, $name, $image_path, $start_date, $end_date,
      $description, $price, $remaining_quota) {
      $start_date = format_datetime($start_date);
      $end_date = format_datetime($end_date);

    return "
      <div class='card tour-card'>

        <div class='card-body'>
          <image class='card-image' src='./images/$image_path'>
          <h5 class='card-title'><b>$name</b></h5>
          <p class='card-text'>
            $description<br><br>
            <b>Tour Start:</b> $start_date<br>
            <b>Tour End:</b> $end_date<br><br>
            
          </p>
        </div>

        <div class='card-footer bg-white'>
          <div class='right'>
            <a href='view_tour.php?id=$id' class='btn btn-secondary'>View Details</a>
            <a href='reserve_tour.php?id=$id' class='btn btn-primary'>Make Reservation</a>
          </div>
          There are <b>$remaining_quota</b> places remaining.<br>  
          <b>$price TL</b>
        </div>

      </div>
    ";
  }

?>

