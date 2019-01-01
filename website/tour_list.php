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
        $tours .= "
              <div>
                $tour_name<br>
                $tour_image_path <br><br>
                Start: $tour_start_date <br>
                End: $tour_end_date <br><br>
                $tour_description <br>
                <br>
              </div>
        ";
        }
        if ($tours == "") {
          $tours = "&lt;empty&gt;";
        }
        return $tours;
  }

  function filtersAdd()
  {
  $filterQuery = "SELECT TourPreview.* FROM Tag, TourTags , TourPreview WHERE TourTags.tour_ID = TourPreview.tour_ID AND tag_ID = Tag.ID ";
  $orderby = "TourPreview.price" ;
  $ordering = "DESC";
  foreach( $_GET as $key => $value )
  {
      echo "Key ".$key." Value ".$value."<br>";
      if( $key == "query" )
      {
        $filterQuery.="AND ( TourPreview.name LIKE CONCAT('%','".$value."','%')";
        $filterQuery.=" OR TourPreview.description LIKE CONCAT('%','".$value."','%')) ";
      }
      if( $key == "orderby")
        $orderby = $value;
      if( $key == "ordering")
        $ordering = $value;
      if( $key == "start" )
        $filterQuery .= "AND TourPreview.start_date > '".$value."' " ;
      if( $key == "end" )
        $filterQuery .= "AND TourPreview.end_date < '".$value."' " ;
      if( $key == "priceMax" )
        $filterQuery .= "AND TourPreview.price < '".$value."' " ;
      if( $key == "priceMin" )
        $filterQuery .= "AND TourPreview.price > '".$value."' " ;
      if( $value == "true" )
  $filterQuery .= "AND Tag.name ='".$key."' ";
  }
  $filterQuery .= "ORDER BY ".$orderby." ".$ordering." ;";
  return $filterQuery;
  }

  function addTags(){
    $tag_query = "SELECT DISTINCT name FROM Tag";
    $db = $GLOBALS["db"];
    $tag_result = mysqli_query($db, $tag_query);
    $tags = "";
    while ($row = $tag_result->fetch_assoc()) {
      $name = $row["name"];
      $tags .= $name.": <input type=\"checkbox\" name=\"".$name."\"";
      $tags .= isset($_GET[$name]) && $_GET[$name] == "true" ? " checked " : '';
      $tags .= "><br>";
    }
    if ($tags == "") {
      $tags = "&lt;empty&gt;";
    }
    return $tags;
  }
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

      <div class="inner-content">
        <h1 class="home-title">Tours</h1>
        <hr>

        <?php	
          $queryText = isset($_GET['query']) ? $_GET['query'] : '';
        ?>
      
        <input type="text" value=<?php echo $queryText?>><br>

        <?php
          echo addTags();
        ?>

        start: <input type="date"
        <?php echo isset($_GET['start']) ? "value=\"".$_GET['start']."\"" : ''; ?> > <br>
      
        end: <input type="date"
        <?php echo isset($_GET['end']) ? "value=\"".$_GET['end']."\"" : ''; ?> > <br>
      
        <input class=" btn" type="submit" value="Search"/>

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
