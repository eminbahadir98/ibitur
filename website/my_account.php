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

            $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);

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

                $update_query = "UPDATE CustomerTelephones SET telephone_no = $phone_number  WHERE customer_ID = $current_id; ";
                $update_result = mysqli_query($db, $update_query);
                
                if($update_result) {
                    echo "SuccessfulPhone";
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

            echo $add_dep_query;

            $add_rep_result = mysqli_query($db, $add_dep_query);
        }
        else if(isset($_POST['remove-submit'])) {
            echo "here";
            $checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();
            $remove_complete = false;
            foreach($checkboxes as $value) {
                echo "Here: " . $value;

                $remove_include_query = "DELETE
                FROM IncludedDependents
                WHERE dependent_ID = $value;";
                $remove_include_result = mysqli_query($db, $remove_include_query);

                $remove_dep_query = "DELETE
                FROM Dependent
                WHERE Dependent.customer_ID = $current_id
                AND national_ID = $value;";
                echo $remove_dep_query;
                $remove_dep_result = mysqli_query($db, $remove_dep_query);
            }

            $remove_complete = true;
        }
        else if(isset($_POST['redeem-submit'])) {
            $cur_promo_code = $_POST['promo_code'];
            echo "Here: " . $cur_promo_code;
            $get_promotion_query = "select * from PromotionCard where promo_code = '$cur_promo_code';";
            $get_promo_result = mysqli_query($db, $get_promotion_query);

            if($get_promo_result->num_rows == 0) {
                echo "Invalid Promotion Code.";
            }
            else {
                $check_code_query = "select * from CustomerPromotionCards where promo_code = '$cur_promo_code';";
                $check_code_result = mysqli_query($db, $check_code_query);

                if($check_code_result->num_rows != 0) {
                    echo "This promotion card is already taken.";
                }
                else {
                    $add_promotion_query = "insert into CustomerPromotionCards values('$cur_promo_code',$current_id);";
                    $add_promotion_result = mysqli_query($db, $add_promotion_query);
                    if($add_promotion_result) {
                        echo "Promotion Card Added Successfully";
                    }
                }
                
            }
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
        $email = $profile_settings_data['email'];

        $phone_query = "select telephone_no from CustomerTelephones where customer_ID=$current_id;";

        $phone_result = mysqli_query($db, $phone_query);

        if($phone_result->num_rows != 0) {
            $phone_data = $phone_result->fetch_assoc();
            $phone_number = $phone_data['telephone_no'];
        }
        else {
            $phone_number = "";
        }
        
        $dep_first_name = "";
        $dep_last_name = "";
        $dep_national_id = "";
        $dep_dob = "";
        $dep_gender = "";
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
                echo get_header($current_fullname, $current_is_staff);
             } else {
                header("location: login.php");
             }
        ?>
        
        <div class="inner-content">
            <h1> My Account - <?php echo $current_username ?> </h1>
            <hr>

            <h3> Active Reservations </h3>
            <div>
                <?php
                    echo getActiveReservations();
                ?>
            </div>
            <hr>

            <h3> Cancelled Reservations </h3>
            <div>
                <?php
                    echo getCancelledReservations();
                ?>
            </div>
            <hr>

            <h3> Past Reservations </h3>
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
                    
                    <input class='btn right' type='submit' name ='profile-submit' value='Save Changes'/><br><br>
                </div>

                </form>
            </div>

            <div class="dependent-settings">
                <h2> Dependent Travelers </h2>
                <hr>
                
                <?php

                $get_dependent_query = "SELECT first_name, middle_name, last_name, national_ID FROM Dependent
                WHERE Dependent.customer_ID = $current_id;";

                $get_dependent_result = mysqli_query($db, $get_dependent_query);
                
                if($get_dependent_result->num_rows == 0) {
                    echo "<p>This customer does not have any dependents yet.</p>";
                }
                else {
                    echo "<form name='remove-dependent' action='' method='post' >";
                    
                    echo "<table>
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

                    echo "<input class='submit-button btn' type='submit' name='remove-submit' value='Remove Dependent(s)'/>";

                    echo "</form>";
                }

                ?>

                <h4> Add New Dependent </h4>
                <hr>
                <form name='dep-add-form' action='' method ='post'>
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
                    
                    <input class='btn right' type='submit' name ='dependent-add-submit' value='Add Dependent'/><br><br>
                </div>
                </form>
            </div>


            <div class="booking-pts">
                    <h2> Booking Points </h2>
                    <hr>
                    <p>Currently you have <?php echo $booking_pts ?> booking points</p>
            </div>

            <div class="promotion-settings">
                <h2> Promotion Cards </h2>
                <hr>
                <?php
                    $promotion_query = "select * from PromotionCard natural join CustomerPromotionCards;";
                    $promotion_result = mysqli_query($db, $promotion_query);

                    if($promotion_result->num_rows == 0) {
                        echo "<p>You don't have any promotion cards yet.</p>";
                    }
                    else {
                        echo "<table>
                        <tr>
                            <th> Promotion Code </th>
                            <th> Discount Percent </th>
                        </tr>";
                        while($row = $promotion_result->fetch_assoc()) {

                            echo "<tr>";
                            echo "<td>" . $row['promo_code'] . "</td>";
                            echo "<td>" . $row['discount_percent'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                    }

                ?>

                <form name='redeem' action='' method='post'>
                <input class='form-control input-field' type='text' name='promo_code' value = ''/>
                <input class='submit-button btn' type='submit' name='redeem-submit' value='Redeem Promotion Code'/>
                </form>
            </div>

            <div class="account-settings">
                <h2> Account Details </h2>
                <hr>
                
                <?php
                    echo "<label>Username: $current_username</label><br><br>";
                    echo "<label>E-mail: $email</label><br><br>";
                ?>
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