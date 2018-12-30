<?php
    include('util/session.php');
    include('util/visuals.php');
    include('util/forms.php');

    function updateProfile() {
        
    }

    function checkNationality() {
        //TODO
        return true;
    }

    if($logged_in == false) {
        header("location: login.php");
    }
    else { 
        if( isset($_POST['profile-submit'] ) ) {
            echo "HERE";

            $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
            $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
            $nationality = mysqli_real_escape_string($db, $_POST['nationality']);
            $national_id = mysqli_real_escape_string($db, $_POST['national_id']);
            $gender = mysqli_real_escape_string($db, $_POST['gender']);
            $date_of_birth = mysqli_real_escape_string($db, $_POST['date_of_birth']);

            if(checkNationality()) {
                $update_query = " UPDATE CustomerAccount natural join Account
                SET first_name = '$first_name', middle_name = '', last_name = '$last_name',
                nationality = '$nationality', national_ID = $national_id,
                gender= '$gender', date_of_birth = '$date_of_birth' WHERE CustomerAccount.ID = $current_id; ";
                
                echo $update_query;

                $update_result = mysqli_query($db, $update_query);

                if($update_result) {
                    echo "Successful";
                }
            }
            else {
                // Nationality exists.
            }
            
            

        }
        else if(isset($_POST['dependent-add-submit'])) {
            echo "Inside Dependent-Add";

            $dep_first_name = mysqli_real_escape_string($db, $_POST['dep_first_name']);
            $dep_last_name = mysqli_real_escape_string($db, $_POST['dep_last_name']);
            $dep_national_id = mysqli_real_escape_string($db, $_POST['dep_national_id']);
            $dep_dob = mysqli_real_escape_string($db, $_POST['dep_dob']);
            $dep_gender = mysqli_real_escape_string($db, $_POST['dep_gender']);

            $add_dep_query = "INSERT INTO Dependent(customer_ID, national_ID, gender, date_of_birth, first_name, last_name)
            VALUES( $current_id , $dep_national_id, '$dep_gender', '$dep_dob', '$dep_first_name', '$dep_last_name' );";

        }

        $profile_settings_data_query = "select * from Account A, CustomerAccount C, Country CR where A.ID = C.ID and C.nationality = CR.ID and A.username = '" . $_SESSION['session_username'] . "';";
        
        echo $profile_settings_data_query;
        
        $profile_settings_result = mysqli_query($db, $profile_settings_data_query);
        $profile_settings_data = $profile_settings_result->fetch_assoc();
        

        $first_name = " " . $profile_settings_data['first_name'] . " " .  $profile_settings_data['middle_name'];
        $last_name = $profile_settings_data['last_name'];
        $nationality = $profile_settings_data['name'];
        $national_id = $profile_settings_data['national_ID'];
        $date_of_birth = $profile_settings_data['date_of_birth'];
        $gender = $profile_settings_data['gender'];
        $booking_pts = $profile_settings_data['booking_points'];  
    }

    function getReservations($reservation_query) {
        $db = $GLOBALS["db"];
        $reservations_result = mysqli_query($db, $reservation_query);

        $reservations = "";
          while ($row = $reservations_result->fetch_assoc()) {
            $tour_name = $row["name"];
            $tour_description = $row["description"];
            $tour_image_path = $row["image_path"];
            $tour_start_date = $row["start_date"];
            $tour_end_date = $row["end_date"];

            $reservations .= "
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
          
          if ($reservations == "") {
            $reservations = "&lt;empty&gt;";
          }

          return $reservations;
    }

    function getActiveReservations() {
        $current_id = $GLOBALS["current_id"];
        $active_reservation_query = "
            SELECT name, description, image_path, start_date, end_date
            FROM (TourPreview NATURAL JOIN Reservation)
            WHERE Reservation.customer_ID = $current_id
            AND Reservation.cancel_date IS NULL
            AND start_date > NOW();";
        return getReservations($active_reservation_query);
    }

    function getCancelledReservations() {
        $current_id = $GLOBALS["current_id"];
        $cancelled_reservation_query = "
            SELECT name, description, image_path, start_date, end_date
            FROM (TourPreview NATURAL JOIN Reservation)
            WHERE Reservation.customer_ID = $current_id
            AND Reservation.cancel_date IS NOT NULL
            AND start_date > NOW();";
        return getReservations($cancelled_reservation_query);
    }

    function getPastReservations() {
        $current_id = $GLOBALS["current_id"];
        $past_reservation_query = "
            SELECT name, description, image_path, start_date, end_date
            FROM (TourPreview NATURAL JOIN Reservation)
            WHERE Reservation.customer_ID = $current_id
            AND Reservation.cancel_date IS NULL
            AND end_date < NOW();";
        return getReservations($past_reservation_query);
    }

?>

<html>
    <head>
      <title>IBITUR - My Account</title>
      <link rel="stylesheet" href="style/style.css"/>
      <link rel="stylesheet" href="lib/bootstrap.min.css"/>
   </head>

   <body class="content">
        <?php
            if ($logged_in) {
                echo get_header($current_fullname);
             } else {
                header("location: login.php");
             }
        ?>
        
        <div class="inner-content">
            <h1> My Account - <?php echo $current_username ?> </h1>
            <hr>

            <h3> Active Reservations </h2>
            <div>
                <?php
                    echo getActiveReservations();
                ?>
            </div>
            <hr>

            <h3> Cancelled Reservations </h2>
            <div>
                <?php
                    echo getCancelledReservations();
                ?>
            </div>
            <hr>

            <h3> Past Reservations </h2>
            <div>
                <?php
                    echo getPastReservations();
                ?>
            </div>
            <hr>
            <br><br>
            
            <div class="profile-settings">
                <h2> Profile Settings </h2>
                <hr>
                <form action="" method="POST">
                <div class="profile-left">
                    <label>First Name</label>
                    <input class='form-control input-field' type='text' name='first_name' value = '<?php echo $first_name ?>'/> <br><br>
                    <label>Nationality</label>
                    <?php
                        echo '<select class="form-control input-field" name="nationality">';
                        $country_query = "SELECT ID, name FROM Country";
                        $country_result = mysqli_query($db, $country_query);
                        while ($row = $country_result->fetch_assoc()) {
                            echo '<option value=';
                            echo $row["ID"];
                            echo ">";
                            echo $row["name"];
                            echo '</option>';
                        }
                        echo '</select> <br><br>';
                    ?>

                    <label>National ID</label>
                    <input class='form-control input-field' type='text' name='national_id' value = '<?php echo $national_id ?>'/> <br><br>
                    <label>Phone Number</label>
                    <input class='form-control input-field' type='text' name='phone_number' value = '<?php echo $phone_number ?>'/> <br><br>
                    
                </div>
                <div class="profile-left">

                    <label>Last Name:</label>
                    <input class='form-control input-field' type='text' name='last_name' value = '<?php echo $last_name ?>'/> <br><br>
                    <label>Date of Birth:</label>
                    <input class='form-control input-field' type='date' name='date_of_birth' value = '<?php echo $date_of_birth ?>'/> <br><br>
                    <label>Gender:</label>
                    <select class="form-control input-field" name="gender"> 
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                    </select> <br><br>
                    
                    <input class='btn submit-button' type='submit' name ='profile-submit' value='Save Changes'/><br><br>
                </div>

                </form>
            </div>

            <div class="dependent-settings">
                <h2> Dependent Travelers </h2>
                <hr>
                
                <?php 
                    $get_dependent_query = "SELECT first_name, middle_name, last_name FROM Dependent
                    WHERE Dependent.customer_ID = $current_id;";

                    $get_dependent_result = mysqli_query($db, $get_dependent_query);
                    
                    if($get_dependent_result->num_rows == 0) {
                        echo "<p>You do not have any dependents yet.</p>";
                    }
                    else {
                        echo "<table>
                        <tr>
                            <th> First Name </th>
                            <th> Last Name </th>
                        </tr>";
                        while($row = $get_dependent_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['first_name'] . "</td>";
                            echo "<td>" . $row['last_name'] . "</td>";
                            echo "</tr>";
                        }
        
                        echo "</table>";
                    }
                    
                ?>

                <?php echo get_form_btn("Remove Dependent/TODO");?>

                <h4> Add New Dependent </h4>
                <hr>
                <div class="dependent-left">
                    <label>First Name:</label>
                    <input class='form-control input-field' type='text' name='dep_first_name' value = '<?php echo $dep_first_name; ?>'/> <br><br>
                    <label>National ID:</label>
                    <input class='form-control input-field' type='text' name='dep_national_id' value = '<?php echo $dep_national_id; ?>'/> <br><br>
                </div>
                <div class="dependent-right">
                    <label>Last Name:</label>
                    <input class='form-control input-field' type='text' name='dep_last_name' value = '<?php echo $dep_last_name ?>'/> <br><br>
                    <label>Date of Birth:</label>
                    <input class='form-control input-field' type='date' name='dep_dob' value = '<?php echo $dep_dob ?>'/> <br><br>
                    <label>Gender:</label>
                    <select class="form-control input-field" name="dep_gender"> 
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                    </select> <br><br>
                    
                    <input class='btn submit-button' type='submit' name ='dependent-add-submit' value='Add Dependent'/><br><br>
                </div>
            </div>

            <div class="booking-pts">
                    <h2> Booking Points </h2>
                    <hr>
                    <p>Currently you have <?php echo $booking_pts ?> booking points</p>
            </div>

            <div class="promotion-settings">
                <h2> Promotion Cards </h2>
                <hr>
                <p>TODO</p>
            </div>

            <div class="account-settings">
                <h2> Account Details </h2>
                <hr>
                
                <p>TODO: fetch username and email</p>
                <?php
                    echo get_form_btn("Change e-mail");
                    echo get_form_btn("Change password");
                ?>
                
            </div>
                    
        </div>

        <?php
         echo get_footer();
        ?>



   </body>


</html>