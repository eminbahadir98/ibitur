<?php
   include("util/visuals.php");
   include("util/session.php");
   
   $error = "";
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      $username_input = mysqli_real_escape_string($db, $_POST['username']);
      $password_input = mysqli_real_escape_string($db, $_POST['password']);
   }
?>

<html>

   <head>
      <title>IBITUR - Register</title>
      <link rel="stylesheet" href="style/style.css"/>
      <link rel="stylesheet" href="lib/bootstrap.min.css"/>

      <script type="text/javascript">
         function isASCII(str) {
            return /^[\x00-\x7F]*$/.test(str);
         }
         function isAlphaNumeric(str) {
            return /^[a-z0-9]+$/i.test(str);
         }
         function checkInput() {

            var errorDiv = document.getElementById("error-div");
            var email = document.forms["register-form"]["email"].value;
            var username = document.forms["register-form"]["username"].value;
            var password = document.forms["register-form"]["password"].value;
            var password2 = document.forms["register-form"]["password2"].value;
            var first_name = document.forms["register-form"]["first_name"].value;
            var middle_name = document.forms["register-form"]["middle_name"].value;
            var last_name = document.forms["register-form"]["last_name"].value;
            var birthday = document.forms["register-form"]["birthday"].value;
            var gender = document.forms["register-form"]["gender"].value;
            var nationality = document.forms["register-form"]["nationality"].value;
            var national_id = document.forms["register-form"]["national_id"].value;

            console.log("");
            console.log("email = " + email);
            console.log("username = " + username);
            console.log("password = " + password);
            console.log("password2 = " + password2);
            console.log("first_name = " + first_name);
            console.log("middle_name = " + middle_name);
            console.log("last_name = " + last_name);
            console.log("birthday = " + birthday);
            console.log("gender = " + gender);
            console.log("nationality = " + nationality);
            console.log("national_id = " + national_id);

            if (!isAlphaNumeric(username)) {
               errorDiv.innerText = "Username can contain only letters and numbers.";
               return false;
            }
            if (!isASCII(username)) {
               errorDiv.innerText = "Password can contain only ASCII characters.";
               return false;
            }

            return true;
         }
      </script>

   </head>
   
   <body class="content">

      <?php
         if ($logged_in) {
            header("location: index.php");
         } else {
            echo get_header(null);
         }
      ?>
      
      <h1 class="home-title">Create New IBITUR Account</h1>
      <div class="register-div">
         <form name="register-form" action="" onsubmit="return checkInput()" method="post">
            <label>E-Mail:</label>
            <input required class="form-control input-field" type="text" name="email"/> <br><br>
            <label>Username:</label>
            <input required class="form-control input-field" type="text" name="username"/> <br><br>
            <label>Password:</label>
            <input required class="form-control input-field" type="password" name="password"/> <br><br>
            <label>Re-enter Password:</label>
            <input required class="form-control input-field" type="password" name="password2"/> <br><br>
            <hr>
            <label>First Name:</label>
            <input required class="form-control input-field" type="text" name="first_name"/> <br><br>
            <label>Middle Name:</label>
            <input required class="form-control input-field" type="text" name="middle_name"/> <br><br>
            <label>Last Name:</label>
            <input required class="form-control input-field" type="text" name="last_name"/> <br><br>
            <label>Date of Birth:</label>
            <input required class="form-control input-field" type="date" name="birthday" max="2000-01-01"> <br><br>
            
            <label>Gender:</label>
            <select class="form-control input-field" name="gender"> 
              <option>Male</option>
              <option>Female</option>
              <option>Other</option>
            </select> <br><br>

            <label>Nationality:</label>
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

            <label>National ID:</label>
            <input required class="form-control input-field" type="number" name="national_id"/> <br><br>
            
            <hr>
            <input class="submit-button btn" type="submit" value="Register"/>
            
         </form>
         <div id="error-div"><?php echo $error; ?></div>
         
      </div>

      <?php
         echo get_footer();
      ?>
   </body>

</html>
