<?php
  include('util/session.php');
  include('util/visuals.php');

  $top_cities = array();
  $top_city_visists = array();
  $top_countries = array();
  $top_country_revenues = array();

  $city_query_1 = "DROP VIEW IF EXISTS CityPopularity;";
  $city_query_2 = "DROP VIEW IF EXISTS TempTourAssociations;";
  $city_query_3 = "CREATE VIEW TempTourAssociations AS (
    SELECT tour_ID, city_name FROM TourAssociations NATURAL JOIN TourPreview
    WHERE (SELECT CURRENT_DATE + INTERVAL - 1 MONTH) <= start_date AND start_date <= (NOW()) 
  );";
  $city_query_4 = "CREATE VIEW CityPopularity AS (
    SELECT city_name, SUM(used_quota) AS popularity
    FROM TempTourAssociations NATURAL JOIN TourUsedQuotas
    GROUP BY city_name    
    ORDER BY popularity, city_name DESC
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



  $country_query_1 = "DROP VIEW IF EXISTS CountryRevenues;";
  $country_query_2 = "DROP VIEW IF EXISTS TempTourAssociations;";
  $country_query_3 = "CREATE VIEW TempTourAssociations AS (
    SELECT tour_ID, country_name FROM TourAssociations NATURAL JOIN TourPreview
    WHERE (SELECT CURRENT_DATE + INTERVAL - 1 YEAR) <= start_date AND start_date <= (NOW()) 
  );";
  $country_query_4 = "CREATE VIEW CountryRevenues AS (
    SELECT country_name, SUM(price) AS revenue
    FROM TempTourAssociations NATURAL JOIN TourPreview
    GROUP BY country_name
    ORDER BY revenue, country_name DESC
  );";
  $country_query_F = "SELECT * FROM CountryRevenues";

  $country_result_1 = mysqli_query($db, $country_query_1);
  $country_result_2 = mysqli_query($db, $country_query_2);
  $country_result_3 = mysqli_query($db, $country_query_3);
  $country_result_4 = mysqli_query($db, $country_query_4);
  $country_result_F = mysqli_query($db, $country_query_F);
  
  if (mysqli_num_rows($country_result_F) > 0) {
    $i = 0;
    while ($i++ < 10 && $row = $country_result_F->fetch_assoc()) {
      array_push($top_countries, $row["country_name"]);
      array_push($top_country_revenues, $row["revenue"]);
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
        echo get_header(null, false);
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
        <?php
          for($i = 0; $i < count($top_countries); $i++) {
            $rank = $i + 1;
            $country = $top_countries[$i];
            $revenue = $top_country_revenues[$i];
            echo "<b>$rank.</b> $country [~$revenue TL]<br>";
          }
        ?>
      </div>

    </div>

    <?php
      echo get_footer();
    ?>

  </body>

</html>
