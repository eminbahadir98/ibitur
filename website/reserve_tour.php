<?php
    include('util/session.php');
    include('util/visuals.php');
    include('util/forms.php');


    $profile_settings_data_query = "select * from Account A, CustomerAccount C, Country CR, CustomerTelephones T where A.ID = C.ID and C.nationality = CR.ID and C.ID = T.customer_ID and A.username = '" . $_SESSION['session_username'] . "';";

    //echo $profile_settings_data_query;

    $profile_result = mysqli_query($db, $profile_settings_data_query);
    $profile_data = $profile_result->fetch_assoc();
    $profile_found = false;

    if($profile_result->num_rows != 0) {
        $profile_found = true;
        $first_name = $profile_data['first_name'];
        $national_id = $profile_data['national_ID'];
        $phone = $profile_data['telephone_no'];
        $last_name = $profile_data['last_name'];
        $dob = $profile_data['date_of_birth'];
        $gender = $profile_data['gender'];
    }

    if(isset($_POST['reserve-submit'])) {
        
        echo $first_name;

        $checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();
        
        foreach($checkboxes as $value) {
            echo "Here: " . $value;
        }
    }

?>

<html>

<head>
<title>IBITUR - Customer Profile</title>
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

        $tour_found = false;
        if(isset($_GET['id'])) {
            $tour_id = $_GET['id'];
            $tour_preview_query = "SELECT * FROM TourPreview WHERE tour_ID = $tour_id";
            $tour_preview_result = mysqli_query($db, $tour_preview_query);
            
            if($tour_preview_result && mysqli_num_rows($tour_preview_result) == 1) {
                $tour_found = true;
                $result_row = $tour_preview_result->fetch_assoc();
                $tour_name = $result_row["name"];
                $tour_description = $result_row["description"];
                $tour_image_path = $result_row["image_path"];
                $tour_price = $result_row["price"];
                $tour_start_date = $result_row["start_date"];
                $tour_end_date = $result_row["end_date"];
                $quota = $result_row["remaining_quota"];
            }
            else {
                $tour_found = false;
            }
        }
    ?>

    <div class="inner-content">
        <h1> Tour Reservation </h1>
        <hr>

        <div>
        <?php
            if(!$tour_found) {
                echo "<h2> Tour Not Found </h2>";
            }
            else {
                echo " $tour_image_path <br><br>
                Start: $tour_start_date <br>
                End: $tour_end_date <br><br>
                $tour_description <br>
                <br>
                $tour_price TL<br><br>";
                if($quota <= 5) {
                    echo "Last $quota spots! <br><br>";
                }
            }
        ?>
        </div>

        <h3> Reservation Information </h3>
        <hr>
        <div class = "reservation-info">

            <div class = "reservation-info-left">
                <?php
                    if($profile_found) {
                        echo "<p>First Name: $first_name</p>";
                        echo "<p>National ID: $national_id</p>";
                        echo "<p>Phone Number: $phone</p>";
                    }
                    else {
                        echo "<p>Profile Data Not Available.</p>";
                    }
                    
                ?>

            </div>

            <div class = "reservation-info-left">

                <?php
                    if($profile_found) {
                        echo "<p>Last Name: $last_name</p>";
                        echo "<p>Date of Birth: $dob</p>";
                        echo "<p>Gender: $gender</p>";
                    }  
                ?>

            </div>
        </div>

        <h3> Dependent Information </h3>
        <hr>
        <?php

                $get_dependent_query = "SELECT first_name, middle_name, last_name, national_ID FROM Dependent
                WHERE Dependent.customer_ID = $current_id;";

                $get_dependent_result = mysqli_query($db, $get_dependent_query);
                
                if($get_dependent_result->num_rows == 0) {
                    echo "<p>This customer does not have any dependents yet.</p>";
                }
                else {
                    echo "<form name='done-reservation' action='view_tour.php?id=$tour_id' method='post' >";
                    
                    echo "<table class='table table-bordered'>
                    <tr>
                        <th> First Name </th>
                        <th> Last Name </th>
                        <th> Include </th>
                    </tr>";
                    $count = 0;
                    while($row = $get_dependent_result->fetch_assoc()) {

                        $temp_check = "<input type='checkbox' name='checkbox[]' value=' " . $row['national_ID'] . "' /> ";

                        echo "<tr>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $temp_check . "</td>";
                        echo "</tr>";

                        $count = $count + 1;
                    }

                    echo "</table>";

                    echo "<br><input class='submit-button btn' type='submit' name='reserve-submit' value='Reserve Tour'/>";

                    echo "</form>";
                }


            ?>
        </div>
        <?php
            echo get_footer();
        ?>
    

</body>


</html>