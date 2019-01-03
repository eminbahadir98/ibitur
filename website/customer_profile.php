<?php
    include('util/session.php');
    include('util/visuals.php');
    include('util/forms.php');

    if (isset($_GET['id'])) {
        $cust_id = $_GET['id'];
    }

    $customer_query = "SELECT first_name, middle_name, last_name, email,telephone_no
    FROM Account, customertelephones  WHERE Account.ID = $cust_id AND customer_ID = $cust_id;";

    $customer_result = mysqli_query($db, $customer_query);
    $customer_data = $customer_result->fetch_assoc();

    $first_name = $customer_data['first_name'];
    $middle_name = $customer_data['middle_name'];
    $last_name =$customer_data['last_name'];
    $email = $customer_data['email']; 
    $phone_no = $customer_data['telephone_no'];

?>

<html>

<head>
<title>IBITUR - Customer Profile</title>
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

        <h1>Customer Profile</h1>
        <hr>
        <br><br>

        <div class= "profile ">
            <h4> General Information </h4>
            <hr>
            <label><b>First Name:</b> <?php echo $first_name?></label><br>
            <label><b>Middle Name:</b> <?php echo $middle_name?></label><br>
            <label><b>Last Name:</b> <?php echo $last_name?> </label><br>
            <label><b>Email:</b> <?php echo $email?></label>
            <a href=<?php echo "mailto:$email"?>>[Send email]</a><br>
            <label><b>Phone Number:</b> <?php echo $phone_no?></label>
            <a href=<?php echo "tel:$phone_no"?>>[Make call]</a><br>
        </div>

        <br><br>
        <div class= "dependents">
            <h4> Dependents </h4>
            <hr>
            <?php

                $get_dependent_query = "SELECT first_name, middle_name, last_name, national_ID FROM Dependent
                WHERE Dependent.customer_ID = $cust_id;";

                $get_dependent_result = mysqli_query($db, $get_dependent_query);
                
                if($get_dependent_result->num_rows == 0) {
                    echo "<p>This customer does not have any dependents.</p>";
                }
                else {
                    echo "<table class='table table-bordered'>
                    <tr>
                        <th> National ID </th>
                        <th> First Name </th>
                        <th> Middle Name </th>
                        <th> Last Name </th>
                    </tr>";
                    while($row = $get_dependent_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['national_ID'] . "</td>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['middle_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }
            ?>

        </div>

    </div>

    <?php
        echo get_footer();
    ?>

</body>

</html>
