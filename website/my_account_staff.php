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

        if(isset($_POST['email-submit'])) {
            $new_email = $_POST['new_email'];
            $change_email_query = "UPDATE Account SET email='$new_email' where ID = '$current_id';";
            $change_email_result = mysqli_query($db, $change_email_query);
        }
        else if(isset($_POST['newpass-submit'])) {
            $old_pass = $_POST['old_pass'];
            $new_pass = $_POST['new_pass'];

            $check_pass_query = "select ID from account where ID='$current_id' and passwd='$old_pass';";
            $check_pass_result = mysqli_query($db, $check_pass_query);

            if($check_pass_result->num_rows == 1) {
                $change_pass_query = "UPDATE Account SET passwd='$new_pass' where ID='$current_id';";
                $change_pass_result = mysqli_query($db, $change_pass_query);
            }
        }
    }
?>

<html>
    <head>
      <title>IBITUR - My Account</title>
      <link rel="stylesheet" href="style/style.css"/>
      <link rel="stylesheet" href="lib/bootstrap.min.css"/>
        <script type="text/javascript">
            function isASCII(str) {
                var re = /^[\x00-\x7F]*$/;
                return re.test(str);
            }
            function isAlphaNumeric(str) {
                var re = /^[a-z0-9]+$/;
                return re.test(str);
            }
            function isValidEmail(email) {
                var re = /^\S+@\S+$/;
                return re.test(email);
            }
            function showError(message, place) {
                var errorDiv = document.getElementById(place);
                errorDiv.innerHTML =
                    "<div class='alert alert-warning' role='alert'>" +
                    message +
                    "</div>";
            }

            function checkPass() {
                var password1 = document.forms["change-pass"]["new_pass"].value;
                var password2 = document.forms["change-pass"]["confirm_pass"].value;

                if (!isASCII(password1)) {
                    showError("Password can contain only ASCII characters.", "account-settings-error-div");
                    return false;
                }
                if (password1.length < 6) {
                    showError("Password should contain minimum of 6 characters.","account-settings-error-div");
                    return false;
                }
                if (password1 != password2) {
                    showError("The entered passwords do not match.","account-settings-error-div");
                    return false;
                }
                return true;
            }

            function checkEmail() {
                var email = document.forms["change-email"]["new_email"].value;
                if(!isValidEmail(email)) {
                    showError("E-mail is not valid.", "account-settings-error-div");
                    return false;
                }
                return true;
            }
            </script>
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

                <hr>

                <form name='change-email' onSubmit='return checkEmail();' method='post'>
                    <label>New Email: </label>
                    <input class='form-control input-field' type='text' name='new_email' value = ''/><br><br>
                    <input class='btn right' type='submit' name='email-submit' value='Change Email'/>
                </form>
                <br><br>
                <form name='change-pass' onSubmit='return checkPass();' method='post'>
                    <label>Old Password: </label>
                    <input class='form-control input-field' type='password' name='old_pass' value = ''/><br><br>
                    <label>New Password: </label>
                    <input class='form-control input-field' type='password' name='new_pass' value = ''/><br><br>
                    <label>Confirm New Password: </label>
                    <input class='form-control input-field' type='password' name='confirm_pass' value = ''/><br><br>
                    <input class='btn right' type='submit' name='newpass-submit' value='Change Password'/>
                </form>


            </div>


        </div>

        <br><br>
        <div id="account-settings-error-div"> </div>


        <?php
         echo get_footer();
        ?>



   </body>


</html>