<?php
    include('util/session.php');
    include('util/visuals.php');
?>

<html>
   
    <head>
        <title>IBITUR - My Tours</title>
        <link rel="stylesheet" href="style/style.css"/>
        <link rel="stylesheet" href="lib/bootstrap.min.css"/>
    </head>
   
   <body class="content">

        <?php
            if ($logged_in && $current_is_staff) {
                echo get_header($current_fullname, $current_is_staff);
            } else {
                header("location: login.php");
            }
        ?>

        <div class="inner-content">

        <h1>My Tours</h1>
        <hr>

        <?php
        $tours_query = "SELECT T.ID FROM Tour T, Account A WHERE T.creator_ID = A.ID
            AND A.username ='".$_SESSION['session_username']."';";

        $tours_result = mysqli_query($db,$tours_query);
        if ($tours_result->num_rows > 0) {
            while ($row = $tours_result->fetch_assoc()) {
                $tour_query = "SELECT * FROM TourPreview WHERE tour_ID =".$row["ID"].";";
                $tour_result = mysqli_query($db,$tour_query);
                if ($tour_result->num_rows == 1) {
                    $tour_row = $tour_result->fetch_assoc();

                    $tour_id = $row["ID"];
                    $tour_name = $tour_row["name"];
                    $tour_description = $tour_row["description"];
                    $tour_image_path = $tour_row["image_path"];
                    $tour_price = $tour_row["price"];
                    $tour_start_date = $tour_row["start_date"];
                    $tour_end_date = $tour_row["end_date"];
                    $tour_remaining_quota = $tour_row["remaining_quota"];
                
                    $tour_summary_card = get_tour_summary_card($tour_id, $tour_name ,
                        $tour_image_path, $tour_start_date, $tour_end_date, $tour_description,
                        $tour_price, $tour_remaining_quota);

                    echo $tour_summary_card;
                }
            }
        } else {
            echo "<h3>You have not created any tour yet.</h3>";
        }
?>

      </div>

      <?php
         echo get_footer();
      ?>

   </body>
   
</html>

