<?php

  function get_header($username, $is_staff) {
    $my_tours_part = $is_staff ? "<a href = 'my_tours.php'>My Tours</a>" 
        : "<a href = 'my_reservations.php'>My Reservations</a>";
    $create_tour_part = $is_staff ? "<a href = 'tour_adding.php'>Create New Tour</a> |" : "";

    if ($username != null) {
      $profile_bar =
        "Welcome <b>$username</b> |
        $create_tour_part
        $my_tours_part |
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
      <a href = 'tours.php'>Tours</a> |
      <a href = 'top_lists.php'>Top Lists</a>
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

  function get_remaining_quota_text($remaining_quota) {
    if ($remaining_quota < 1) {
      return "The quota is full.";
    } else if ($remaining_quota == 1) {
      return " There is only <b>ONE</b> place remaining!";
    } else if ($remaining_quota < 5) {
      return " There are only <b>$remaining_quota</b> places remaining!";
    }
    return "There are <b>$remaining_quota</b> places remaining.";
  }

  function get_tour_card_body_skeleton($has_linked_title, $id, $name, $image_path, $start_date, $end_date, $description) {
    
    $start_date = format_datetime($start_date);
    $end_date = format_datetime($end_date);
    $title_part = $has_linked_title ? 
        "<h5 class='card-title'><b><a href='view_tour.php?id=$id'>$name</a></b></h5>"
        : "<h5 class='card-title'><b>$name</b></h5>";

    return "
        <image class='card-image' src='./images/$image_path'>
        $title_part
        <p class='card-text'>
          $description<br><br>
          <b>Tour Start:</b> $start_date<br>
          <b>Tour End:</b> $end_date
        </p>
    ";
  }

  function get_tour_card_footer_skeleton($remove_buttons, $reserved, $is_staff, $id, $price, $remaining_quota) {
    
    $reservation_button = $reserved ?
      "<button class='btn' disabled>Reserved</button>" :
      "<a href='reserve_tour.php?id=$id' class='btn btn-primary'>Make Reservation</a>";
    
    $reservation_button = !$is_staff ? $reservation_button :
      "<a href='manage_tour.php?id=$id' class='btn btn-primary'>Manage Tour</a>";

    $button_part = $remove_buttons ? "" :
      "<div class='right'>
          <a href='view_tour.php?id=$id' class='btn btn-secondary'>View Details</a>
          $reservation_button
        </div>";
    $remaining_quota_text = get_remaining_quota_text($remaining_quota);
    return "
      $remaining_quota_text<br>  
      <b>$price TL</b>
      $button_part
    ";
  }

  function get_tour_summary_card($id, $name, $image_path, $start_date, $end_date, $description,
      $price, $remaining_quota) {
    
    $tour_card_body_skeleton = get_tour_card_body_skeleton(true, $id, $name, $image_path, $start_date, $end_date, $description);
    $tour_card_footer_skeleton = get_tour_card_footer_skeleton(true, false, false, $id, $price, $remaining_quota);

    return "
      <div class='card tour-card'>
        <div class='card-body'>
          $tour_card_body_skeleton
          $tour_card_footer_skeleton
        </div>
      </div>
    ";
  }

  function get_tour_preview_card($id, $name, $image_path, $start_date, $end_date, $description) {
    $tour_card_body_skeleton = get_tour_card_body_skeleton(true, $id, $name, $image_path, $start_date, $end_date, $description);
    return "
      <div class='card tour-card'>
        <div class='card-body'>
          $tour_card_body_skeleton
        </div>
      </div>
    ";
  }

  function get_tour_purchase_card($is_staff, $reserved, $id, $name, $image_path, $start_date, $end_date, $description,
      $price, $remaining_quota) {
    
    $tour_card_body_skeleton = get_tour_card_body_skeleton(false, $id, $name, $image_path, $start_date, $end_date, $description);
    $tour_card_footer_skeleton = get_tour_card_footer_skeleton(false, $reserved, $is_staff, $id, $price, $remaining_quota);

    return "
      <div class='card tour-card'>
        <div class='card-body'>
          $tour_card_body_skeleton
        </div>
        <div class='card-footer bg-white'>
          $tour_card_footer_skeleton
        </div>
      </div>
    ";
  }

  function get_tour_details_card($button1, $button2, $id, $name, $image_path, $start_date, $end_date, $description,
      $price, $remaining_quota) {
       
        $tour_card_body_skeleton = get_tour_card_body_skeleton(false, $id, $name, $image_path, $start_date, $end_date, $description);
        $tour_card_footer_skeleton = get_tour_card_footer_skeleton(true, false, false, $id, $price, $remaining_quota);
    
        return "
          <div class='card tour-card'>
            <div class='card-body'>
              $tour_card_body_skeleton
            </div>
            <div class='card-footer bg-white'>
              $tour_card_footer_skeleton
              <div class='right'>
                $button1
                $button2
              </div>
            </div>
          </div>
        ";
  }


?>

