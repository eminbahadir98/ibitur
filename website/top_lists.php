<?php
  include('util/session.php');
  include('util/visuals.php');

  $top_cities = array();
  $top_city_visists = array();

  $city_query_1 = "DROP VIEW IF EXISTS CityPopularity;";
  $city_query_2 = "DROP VIEW IF EXISTS TempTourAssociations;";
  $city_query_3 = "CREATE VIEW TempTourAssociations AS (
    SELECT tour_ID, city_name FROM TourAssociations NATURAL JOIN TourPreview
    WHERE TRUE 
  );";
  $city_query_4 = "CREATE VIEW CityPopularity AS (
    SELECT city_name, SUM(resv_no) AS popularity
    FROM TempTourAssociations NATURAL JOIN ReservationCounts
    GROUP BY city_name    
    ORDER BY popularity DESC
  );";
  $city_query_F = "SELECT * FROM CityPopularity";

  $city_result_1 = mysqli_query($db, $city_query_1);
  $city_result_2 = mysqli_query($db, $city_query_2);
  $city_result_3 = mysqli_query($db, $city_query_3);
  $city_result_4 = mysqli_query($db, $city_query_4);
  $city_result_F = mysqli_query($db, $city_query_F);
  
  if (mysqli_num_rows($city_result_F) > 0) {
    $i = 0;
    while ($i++ < 10 && $row = $city_result_F->fetch_assoc()) {
      array_push($top_cities, $row["city_name"]);
      array_push($top_city_visists, $row["popularity"]);
    }
  }

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
        <?php
          for($i = 0; $i < count($top_cities); $i++) {
            $rank = $i + 1;
            $city = $top_cities[$i];
            $visit = $top_city_visists[$i];
            echo "<b>$rank.</b> $city [~$visit visits]<br>";
          }
        ?>
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
