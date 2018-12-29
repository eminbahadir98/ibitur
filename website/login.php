<?php
   include("util/configurations.php");
   include("util/visuals.php");
   session_start();
   
   $error = "";
   
   $is_staff_login = false;
   if (isset($_GET['staff'])) {
      $is_staff_login = true;
   }

   if($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $username_input = mysqli_real_escape_string($db, $_POST['username']);
      $password_input = mysqli_real_escape_string($db, $_POST['password']);
      
      $login_query = "SELECT ID, username, first_name, middle_name, last_name 
            FROM Account WHERE username = '$username_input' AND passwd = '$password_input'";
      $login_result = mysqli_query($db, $login_query);
      $login_succeed = (mysqli_num_rows($login_result) == 1);
      $check_succeed = false;

      if ($login_succeed) {
         $result_row = $login_result->fetch_assoc();
         $account_table = $is_staff_login ? "StaffAccount" : "CustomerAccount";
         $check_query = 'SELECT ID FROM ' . $account_table . ' WHERE ID = ' . $result_row["ID"];
         $check_result = mysqli_query($db, $check_query);
         $check_succeed = (mysqli_num_rows($check_result) == 1);
      }

      if ($check_succeed) {
         $_SESSION['session_username'] = $result_row["username"];
         $_SESSION['session_fullname'] = $result_row["first_name"]
               . " " . $result_row["middle_name"]
               . " " . $result_row["last_name"];
         header("location: index.php");
      }
      
      if (!$login_succeed) {
         $error = "Username and password did not matched.";
      } else if (!$check_succeed) {
         $error = "This is not a " . ($is_staff_login ? "staff" : "customer") . " account.";
      }

   }
?>

<html>

   <head>
      <title>IBITUR - Login</title>
      <link rel="stylesheet" href="style/style.css"/>
      <link rel="stylesheet" href="lib/bootstrap.min.css"/>

      <script type="text/javascript">
         function checkEmpty() {
            var username = document.forms["login-form"]["username"].value;
            var password = document.forms["login-form"]["password"].value;
            if (username == "" || username == null) {
               alert("Please enter your username.");
               return false;
            }
            if (password == "" || password == null) {
               alert("Please enter your password.");
               return false;
            }
            return true;
         }
      </script>

   </head>
   
   <body class = "content">

      <?php
         echo get_header(false, "");
         if ($is_staff_login) {
            echo '<h1 class="home-title">IBITUR Staff Login</h1>';
         } else {
            echo '<h1 class="home-title">IBITUR Login</h1>';
         }
      ?>
      
      <div class="login-div">
         <form name="login-form" action="" onsubmit="return checkEmpty()" method="post">
            <label>Username:</label>
            <input class="input-field" type="text" name="username"/> <br><br>
            <label>Password:</label>
            <input class="input-field" type="password" name="password"/> <br><br>
            <input class="input-field btn" type="submit" value="Login"/>
         </form>
         <div><?php echo $error; ?></div>

         <?php
            if (! $is_staff_login) {
               echo '<br><a href="login.php?staff=true">Login as staff member</a>';
            }
         ?>
         
      </div>

      <?php
         echo get_footer();
      ?>
   </body>

</html>
