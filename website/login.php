<?php
   include("util/configurations.php");
   include("util/visuals.php");
   session_start();
   
   $error = "";

   if($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $username_input = mysqli_real_escape_string($db, $_POST['username']);
      $password_input = mysqli_real_escape_string($db, $_POST['password']); 
      
      $login_query = "SELECT sid FROM student WHERE sname = '$username_input' and sid = '$password_input'";
      $login_result = mysqli_query($db, $login_query);
      
      if (mysqli_num_rows($login_result) == 1) {
         $_SESSION['session_sid'] = $password_input;
         header("location: welcome.php");
      } else {
         $error = "Username and password did not matched.";
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
      ?>

      <h1 class="home-title">IBITUR Login</h1>
      <div class="login-div">
         <form name="login-form" action="" onsubmit="return checkEmpty()" method="post">
            <label>Username:</label>
            <input class="input-field" type="text" name="username"/> <br><br>
            <label>Password:</label>
            <input class="input-field" type="password" name="password"/> <br><br>
            <input class="input-field btn" type="submit" value="Login"/>
         </form>
         <div><?php echo $error; ?></div>
      </div>

      <?php
         echo get_footer();
      ?>
   </body>

</html>
