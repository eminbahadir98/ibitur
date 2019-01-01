<?php
  include('util/session.php');
  include('util/visuals.php');
  include('util/forms.php');

  function getTours($tours_query) {
    $db = $GLOBALS["db"];
    $tours_result = mysqli_query($db, $tours_query);
    $tours = "";
    while ($row = $tours_result->fetch_assoc()) {
      $tour_ID = $row["tour_ID"];
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
          $tour_image_path <br>
          <a href='view_tour.php?id=$tour_ID'>Details</a> <br><br>
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
      $tags .= "> $name</input><br>";
    }
    if ($tags == "") {
      $tags = "No tags to filter.";
    }
    return $tags;
  }
?>

<html>
   
  <head>
    <title>IBITUR - Tours</title>
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

      $queryText = isset($_GET['query']) ? $_GET['query'] : '';
    ?>

    
    <div class="wider-inner-content">

      <h1>Tours</h1>
      <hr>

      <div class="sidebar">
        <form name="login-form" onsubmit="disableEmptyInputs(this)" action="tour_list.php" method="GET">

          <hr>
          <b>Sort by:</b>
          <fieldset id="orderby">
            <input type="radio" name="orderby" value="price" checked> Price</input><br>
            <input type="radio" name="orderby" value="start_date"
              <?php echo isset($_GET["orderby"]) && $_GET["orderby"] == "start_date" ? "checked" : ""; ?>
            > Start date</input><br>
            <input type="radio" name="orderby" value="end_date"
              <?php echo isset($_GET["orderby"]) && $_GET["orderby"] == "end_date" ? "checked" : ""; ?>
            > End date</input>
          </fieldset>
          <hr>
          <b>Sort in:</b>
          <fieldset id="ordering">
            <input type="radio" name="ordering" value="ASC" checked> Increasing order</input><br>
            <input type="radio" name="ordering" value="DESC"
              <?php echo isset($_GET["ordering"]) && $_GET["ordering"] == "DESC" ? "checked" : ""; ?>
            > Decreasing order</input>
          </fieldset>

          <hr>
          <b>Earliest start date:</b> <input class="form-control" type="date" name="start"
          <?php echo isset($_GET['start']) ? "value='".$_GET['start']."'" : ''; ?> ><br>
        
          <b>Latest end date:</b> <input class="form-control" type="date" name="end"
          <?php echo isset($_GET['end']) ? "value='".$_GET['end']."'" : ''; ?> >

          <hr>
          <b>Min price:</b> <input class="form-control" type="number" name="priceMin"
          <?php echo isset($_GET['priceMin']) ? "value='".$_GET['priceMin']."'" : ''; ?> ><br>

          <b>Max price:</b> <input class="form-control" type="number" name="priceMax"
          <?php echo isset($_GET['priceMax']) ? "value='".$_GET['priceMax']."'" : ''; ?> >

          <hr>
          <b>Tags</b><br>
          <?php echo getTags(); ?>

          <hr>
          <b>Keyword:</b>
          <input class="form-control" type="text" name="query" value=<?php echo $queryText?>>

          <hr>

          <input class=" btn" type="submit" value="Filter"/>
          <br>

        </form>
      
      </div>

      <div class="right-content">

          <?php echo getTours(filtersAdd()); ?>
      
      </div>

    </div>

    <?php
        echo get_footer();
    ?>

  </body>
   
</html>
