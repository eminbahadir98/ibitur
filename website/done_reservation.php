<?php
    include('util/session.php');
    include('util/visuals.php');
    include('util/forms.php');

    if(isset($_GET['id'])) {
        $tour_id = $_GET['id'];
    }
    $reservation_complete = false;

    if(isset($_POST['reserve-submit'])) {
        
        echo $first_name;
        
        $reservation_query = "INSERT INTO Reservation(customer_ID, tour_ID, issue_date, payment_status, cancel_date)
        VALUES($current_id, $tour_id, NOW(), 'UNPAID', NULL);";

        $reservation_result = mysqli_query($db, $reservation_query);
        $reservation_result = true;
        
        if($reservation_result) {
            $reservation_id_query = "select ID from reservation where customer_ID = $current_id and tour_ID=$tour_id;";
            $reservation_id_result = mysqli_query($db, $reservation_id_query);
            $reservation_id_data = $reservation_id_result->fetch_assoc();
            
            if($reservation_id_result->num_rows == 1) {
                $rez_id = $reservation_id_data['ID'];
            }
            else {
                $rez_id = -1;
            }
        }
        
        if($rez_id != -1) {
            $checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();
        
            foreach($checkboxes as $value) {
                echo "Here: " . $value;
                $ins_dep_query = "insert into IncludedDependents(reservation_ID, dependent_ID) values($rez_id, $value);";
                $ins_dep_result = mysqli_query($db, $ins_dep_query);
            }

            $reservation_complete = true;

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
    ?>

    <h1> Tour Reservation </h1>
    <hr>

    <?php
        if($reservation_complete) {
            echo "<h2>Resevation Completed Succesfully.</h2>";
        }
        else {
            echo "<h2>Reservation Failure: Something Went Wrong</h2>";
        }
    ?>

    <form action='payment.php' method='post'>
        <?php
            echo "<input type='hidden' name='rez_id' value=$rez_id /> 
            <input type='hidden' name='tour_id' value='$tour_id' />";
        ?>
        <input class='submit-button btn' type='submit' name='submit' value='Proceed to Payment'/>
    </form>

    <form action='index.php'>
        <input class='submit-button btn' type='submit' name='submit' value='View Reservations'/>
    </form>

    

    
</body>



</html>