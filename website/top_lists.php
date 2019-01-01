<?php
  include('util/session.php');
  include('util/visuals.php');
?>

<html>
  <head>
    <title>IBITUR - Top Lists</title>
    <link rel="stylesheet" href="style/style.css"/>
    <link rel="stylesheet" href="lib/bootstrap.min.css"/>
  </head>

  <body class="content">

    <?php
      if ($logged_in) {
        echo get_header($current_fullname, $current_is_staff);
      } else {
        echo get_header($null, false);
      }
    ?>

    <div class="inner-content">

      <h1>Top Lists</h1>
      <hr>
      
      <br><br>
      <h3> Most visited cities of the month </h3>
      <hr>
      <div class='top-list'>
        <b>1.</b> Istanbul [~7500 visits]<br>
        <b>2.</b> Tokyo [~6800 visits]<br>
      </div>

      <br><br><br><br>
      <h3> Top revenue-making countries of the year </h3>
      <hr>
      <div class='top-list'>
        <b>1.</b> Turkey [~345000 TL]<br>
        <b>2.</b> Japan [~320000 TL]<br>
      </div>

    </div>

    <?php
      echo get_footer();
    ?>

  </body>

</html>
