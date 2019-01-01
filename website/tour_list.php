<?php
  include('util/session.php');
  include('util/visuals.php');
  include('util/forms.php');

  function getTours($tours_query) {
    $db = $GLOBALS["db"];
    $tours_result = mysqli_query($db, $tours_query);
    $tours = "";
    while ($row = $tours_result->fetch_assoc()) {
    $tour_name = $row["name"];
    $tour_description = $row["description"];
    $tour_image_path = $row["image_path"];
    $tour_start_date = $row["start_date"];
    $tour_end_date = $row["end_date"];
    $tour_price = $row["price"];
    $tour_remaining_quota = $row["remaining_quota"];
    $tours .= "
      <div>
        $tour_name<br>
        $tour_image_path <br><br>
        Start: $tour_start_date <br>
        End: $tour_end_date <br><br>
        $tour_description <br>
        Price: $tour_price <br>
        Remaining spots: $tour_remaining_quota <br>
        <br>
      </div>
      <br><br>
    ";
    }
    if ($tours == "") {
      $tours = "No tours to show.";
    }
    return $tours;
  }

  function filtersAdd()
  {
    $filterQuery = "SELECT * FROM TourPreview TP WHERE TRUE ";
    $orderby = "TP.price" ;
    $ordering = "DESC";
    foreach( $_GET as $key => $value )
    {
      if( $key == "query" )
      {
        $filterQuery .= "AND ( TP.name LIKE CONCAT('%','".$value."','%')";
        $filterQuery .= " OR TP.description LIKE CONCAT('%','".$value."','%')) ";
      }

      if( $key == "orderby")
        $orderby = $value;
      if( $key == "ordering")
        $ordering = $value;
      if( $key == "start" )
        $filterQuery .= "AND TP.start_date > '".$value."' " ;
      if( $key == "end" )
        $filterQuery .= "AND TP.end_date < '".$value."' " ;
      if( $key == "priceMax" )
        $filterQuery .= "AND TP.price < '".$value."' " ;
      if( $key == "priceMin" )
        $filterQuery .= "AND TP.price > '".$value."' " ;
      if( $value == "true" )
        $filterQuery .= "AND '$key' IN (SELECT Tag.name FROM Tour, TourTags, Tag
          WHERE TP.tour_ID = Tour.ID AND Tour.ID = TourTags.tour_ID AND TourTags.tag_ID = Tag.ID) ";

    }
    $filterQuery .= " ORDER BY ".$orderby." ".$ordering." ;";
    return $filterQuery;
  }

  function getTags() {
    $tag_query = "SELECT DISTINCT name FROM Tag";
    $db = $GLOBALS["db"];
    $tag_result = mysqli_query($db, $tag_query);
    $tags = "";
    while ($row = $tag_result->fetch_assoc()) {
      $name = $row["name"];
      $tags .= "<input type='checkbox' name='$name' value='true'";
      $tags .= (isset($_GET[$name]) && $_GET[$name] == "true") ? " checked " : '';
      $tags .= ">$name</input><br>";
    }
    if ($tags == "") {
      $tags = "No tags to filter.";
    }
    return $tags;
  }
?>

<html>
   
   <head>
      <title>IBITUR - Home</title>
      <link rel="stylesheet" href="style/style.css"/>
      <link rel="stylesheet" href="lib/bootstrap.min.css"/>
      <script>
        function disableEmptyInputs(form) {
          var controls = form.elements;
          var controls_length = controls.length;
          for (var i = 0; i < controls_length; i++) {
            controls[i].disabled = (controls[i].value == '');
          }
        }
      </script>
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
        <h1>Tours</h1>
        <hr>

        <?php	
          $queryText = isset($_GET['query']) ? $_GET['query'] : '';
        ?>
      
        <form name="login-form" onsubmit="disableEmptyInputs(this)" action="tour_list.php" method="GET">

          Search keyword:
          <input name="query" class="form-control" type="text" value=<?php echo $queryText?>> <br>

          Earliest start date: <input class="form-control" type="date" name="start"
          <?php echo isset($_GET['start']) ? "value='".$_GET['start']."'" : ''; ?> > <br>
        
          Latest end date: <input class="form-control" type="date" name="end"
          <?php echo isset($_GET['end']) ? "value='".$_GET['end']."'" : ''; ?> > <br>

          Min price: <input class="form-control" type="number" name="priceMin"
          <?php echo isset($_GET['priceMin']) ? "value='".$_GET['priceMin']."'" : ''; ?> > <br>

          Max price: <input class="form-control" type="number" name="priceMax"
          <?php echo isset($_GET['priceMax']) ? "value='".$_GET['priceMax']."'" : ''; ?> > <br>

          <?php
            echo getTags();
          ?>
          <br>

          <input class=" btn" type="submit" value="Search"/>

        </form>

        <div>
          <?php
              echo getTours(filtersAdd());
          ?>
        </div>
      
      </div>

      <?php
         echo get_footer();
      ?>

   </body>
   
</html>
