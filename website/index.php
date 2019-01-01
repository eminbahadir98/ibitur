<?php
  include('util/session.php');
  include('util/visuals.php');
?>

<html>

  <head>
    <title>IBITUR - Home</title>
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
    ?>


    <?php
      if (isset($_GET['registered'])) {
        echo
        "<div class='alert alert-success' role='alert'>
          You have successfully registered. You can now login to your account.
        </div>";
      }
    ?>

    <h1 class="home-title">IBITUR</h1>

    <div class="home-content">
      <h2>Where do you want to travel?</h2>
        <form action='tour_list.php' method='GET'>
          <input class="form-control home-search" type="text" name="query"/>
          <input type='submit' class='btn' value='Search'>
        </form>
    </div>

    <?php
      echo get_footer();
    ?>

  </body>

</html>
