<?php
  include("util/visuals.php");
  include("util/session.php");
  
  $error = "";
  
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_input = mysqli_real_escape_string($db, $_POST['email']);
    $username_input = mysqli_real_escape_string($db, $_POST['username']);
    $password_input = mysqli_real_escape_string($db, $_POST['password']);
    $first_name_input = mysqli_real_escape_string($db, $_POST['first_name']);
    $middle_name_input = mysqli_real_escape_string($db, $_POST['middle_name']);
    $last_name_input = mysqli_real_escape_string($db, $_POST['last_name']);
    $birthday_input = mysqli_real_escape_string($db, $_POST['birthday']);
    $gender_input = mysqli_real_escape_string($db, $_POST['gender']);
    $nationality_input = mysqli_real_escape_string($db, $_POST['nationality']);
    $national_id_input = mysqli_real_escape_string($db, $_POST['national_id']);

    $check_query = "SELECT username FROM Account WHERE username='$username_input'";
    $check_result = mysqli_query($db, $check_query);
    $user_exists = true;
    if (mysqli_num_rows($check_result) == 0) {
      $user_exists = false;
    }
    $register_succeed = false;

    if (!$user_exists) {
      $register_subquery1 = "INSERT INTO Account(username, email, passwd,
        first_name, middle_name, last_name)
        VALUES('$username_input', '$email_input', '$password_input',
        '$first_name_input', '$middle_name_input', '$last_name_input')";
      $register_subquery2 = "INSERT INTO CustomerAccount(ID, national_ID,
        nationality, gender, date_of_birth)
        VALUES(LAST_INSERT_ID(), '$national_id_input',
        $nationality_input, '$gender_input', '$birthday_input')";
      echo "| $register_subquery2 |";
      $register_subquery1_succeed = mysqli_query($db, $register_subquery1);
      if ($register_subquery1_succeed) {
        $register_subquery2_succeed = mysqli_query($db, $register_subquery2);
      }
      $register_succeed = true;
      if (!$register_subquery1_succeed || !$register_subquery2_succeed) {
        $register_succeed = false;
      }
    }

    if ($register_succeed) {
      header("location: index.php?registered=true");
    }

    if ($user_exists) {
      $error = "Username $username_input is already taken.";
    } else if (!$register_succeed) {
      $error = "An error occured during the registration.";
    }
  }
?>

<html>

  <head>
    <title>IBITUR - Register</title>
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
      function showError(message) {
        var errorDiv = document.getElementById("error-div");
        errorDiv.innerHTML = 
          "<div class='alert alert-warning' role='alert'>" + 
            message +
          "</div>";
      }
      function checkInput() {
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

        if (!isValidEmail(email)) {
          showError("The entered mail is not valid.");
          return false;
        }
        if (!isAlphaNumeric(username)) {
          showError("Username can contain only lowercase letters and numbers.");
          return false;
        }
        if (username.length < 3) {
          showError("Username should contain minimum of 3 characters.");
          return false;
        }
        if (!isASCII(password)) {
          showError("Password can contain only ASCII characters.");
          return false;
        }
        if (password.length < 6) {
          showError("Password should contain minimum of 6 characters.");
          return false;
        }
        if (password != password2) {
          showError("The entered passwords do not match.");
          return false;
        }

        return true;
      }
      //checkInput() ;
    </script>

  </head>
  
  <body class="content">

    <?php
      if ($logged_in) {
        header("location: index.php");
      } else {
        echo get_header(null, false);
      }
    ?>
    
    <h1 class="home-title">Create New IBITUR Account</h1>
    <div class="register-div">
      <form name="register-form" action="" onSubmit="return checkInput();" method="post">
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
        <input class="form-control input-field" type="text" name="middle_name"/> <br><br>
        <label>Last Name:</label>
        <input required class="form-control input-field" type="text" name="last_name"/> <br><br>
        <label>Date of Birth:</label>
        <input required class="form-control input-field" type="date" name="birthday" min="1850-01-01" max="2000-01-01"> <br><br>
        
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
        <input class="right btn" type="submit" value="Register"/>
      </form>
      <br><br>

      <div id="error-div">
      <?php
        if ($error != null) {
          echo 
          "<div class='alert alert-warning' role='alert'>
            $error
          </div>";
        }
      ?>
      </div>
      
    </div>

    <?php
      echo get_footer();
    ?>
  </body>

</html>
