<?php
    include('util/session.php');
    include('util/visuals.php');
    include('util/forms.php');

    if ($logged_in == false) {
        header("location: login.php");
    }
    else if (!$current_is_staff) {
        header("location: my_account.php");
    } else {
        $email_data_query = "SELECT email FROM Account A WHERE A.username = '" . $_SESSION['session_username'] . "';";
        $email_data_result = mysqli_query($db, $email_data_query);
        $email_data = $email_data_result->fetch_assoc();
        $email = $email_data["email"];
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
            <h1> My Account</h1>
            <hr>
            <br><br>
            
            <div class="account-settings">
                <h2> Account Details </h2>
                <hr>
                
                <?php
                    echo "This is a confirmed staff acount.<br><br>";
                    echo "<b>Username:</b> $current_username<br>";
                    echo "<b>Email:</b> $email<br><br>";
                ?>
                <?php
                    echo get_form_btn("Change Email");
                    echo get_form_btn("Change Password");
                ?>
                
            </div>
                    
        </div>

        <?php
         echo get_footer();
        ?>



   </body>


</html>