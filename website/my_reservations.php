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
            header("location: login.php");
         }
      ?>


      <?php
         if (isset($_GET['registered']) && $logged_in) {
            echo
            "<div class='alert alert-success' role='alert'>
               You have successfully registered.
            </div>";
         }
      ?>

      <h1 class="home-title">IBITUR - Tour Reservation</h1>

      <div class="home-content">
        <h2>Your Tours</h2>
	<?php
	  $tours_query = "SELECT tour_ID FROM Tour, Reservation, Account WHERE tour_ID = Tour.ID AND customer_ID = Account.ID AND Account.username ='".$_SESSION['session_username']."';";
	  $tours_result = mysqli_query($db,$tours_query);
	  if($tours_result->num_rows <> 0 )
	  {
		while($row = $tours_result->fetch_assoc()){
	         $tour_query = "SELECT * FROM TourPreview WHERE tour_ID =".$row["tour_ID"].";";
                 $tour_result = mysqli_query($db,$tour_query);
                 if( $tour_result->num_rows == 1 )
                  {
                    $tour_row = $tour_result->fetch_assoc();
		?>
                 <div class="cart">
                   <img src=<?php echo $tour_row["image_path"]; ?> >
                   <label> <?php echo $tour_row["name"]; ?></label>
                   <label> <?php echo $tour_row["start_date"];?></label>
                   <label> <?php echo $tour_row["end_date"];?></label>
                   <label> <?php echo $tour_row["description"]; ?></label>
                   <label> <?php echo $tour_row["price"]."TRY"; ?></label>
		   <a href=<?php echo "view_tour.php?id=".$tour_row["tour_ID"]; ?>>TODO</a>
                 </div>
	<?php
                  }
		}
	  }else{
		echo "<h3>You Have No Reservation.</h3>";
	  }
	?>
      </div>

      <?php
         echo get_footer();
      ?>

   </body>
   
</html>

