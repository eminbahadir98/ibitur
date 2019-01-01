<?php
    include('util/session.php');
    include('util/visuals.php');
    include('util/forms.php');

    if (isset($_GET['id'])) {
        $cust_id = $_GET['id'];
    }

    $customer_query = "SELECT first_name, last_name, email
    FROM Account WHERE Account.ID = $cust_id;";

    $customer_result = mysqli_query($db, $customer_query);
    $customer_data = $customer_result->fetch_assoc();

    $first_name = $customer_data['first_name'];
    $last_name =$customer_data['last_name'];
    $email = $customer_data['email']; 


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

    <div class= "profile ">
        <h4> Profile </h4>
        <hr>
        <label>First Name: <?php echo $first_name?></label><br>
        <label>Last Name: <?php echo $last_name?> </label><br>
        <label>E-mail: <?php echo $email?></label>
        <a href=<?php echo "mailto:$email"?>> Send E-mail</a><br>
        <label>phone: </label>

    </div>

    <div class= "dependents">
        <h4> Dependents </h4>
        <hr>
        <?php

            $get_dependent_query = "SELECT first_name, middle_name, last_name FROM Dependent
            WHERE Dependent.customer_ID = $cust_id;";

            $get_dependent_result = mysqli_query($db, $get_dependent_query);
            
            if($get_dependent_result->num_rows == 0) {
                echo "<p>This customer does not have any dependents yet.</p>";
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

    </div>

</body>

</html>