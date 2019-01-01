<?php

  // TODO : Add a $current_page parameter to show current page as not clickable.
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

?>
