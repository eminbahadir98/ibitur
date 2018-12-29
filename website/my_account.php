<?php
   include('util/session.php');
   include('util/visuals.php');
   include('util/forms.php');
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
                echo get_header($current_fullname);
             } else {
                echo get_header(null);
             }
        ?>
        
        <h1> My Account - Name </h1>
        <hr>
        <h2> Reservations </h2>
        <div>
            <p> Reservation Cards Goes Here...</p>
        </div>
        <h2> Cancelled Reservations </h2>
        <div>
            <p> Reservation Cards Goes Here...</p>
        </div>
        <h2> Past Reservations </h2>
        <div>
            <p> Reservation Cards Goes Here...</p>
        </div>

        
        <div class="profile-settings">
            <h2> Profile Settings </h2>
            <hr>
            <div class="profile-left">
                <?php
                    echo get_input_form("First Name", "first_name");
                    echo get_input_form("Nationality", "nationality");
                    echo get_input_form("National ID", "national_id");
                    echo get_input_form("Phone Number", "phone_number");
                    
                ?>
                
                
            </div>
            <div class="profile-left">
                <?php
                    echo get_input_form("Last Name", "last_name");
                    echo get_input_form("Date of Birth(TODO: Date Picker)", "date_of_birth");
                    echo get_input_form("Gender", "gender");
                    echo get_form_btn("Save Changes");
                ?>
            </div>
        </div>

        <div class="dependent-settings">
            <h2> Dependent Travelers </h2>
            <hr>
            <p>TODO: Show Dependent List here </p>
            <?php echo get_form_btn("Remove Dependent");?>

            <h4> Add New Dependent </h4>
            <hr>
            <div class="dependent-left">
                <?php
                    echo get_input_form("First Name", "first_name");
                    echo get_input_form("National ID", "national_id");
                ?>
            </div>
            <div class="dependent-right">
                <?php
                    echo get_input_form("Last Name", "last_name");
                    echo get_input_form("Date of Birth(TODO: Date Picker)", "date_of_birth");
                    echo get_input_form("Gender", "gender");
                    echo get_form_btn("Add Dependent");
                ?>
            </div>
        </div>

        <div class="payment-settings">
            <h2> Payment Details </h2>
            <hr>
            <p>TODO: Show Saved Credit Cards Here</p>
            <?php echo get_form_btn("Set as Default");?>

            <h4> Add New Credit Card </h4>
            <hr>
            <div class="payment-left">
                <?php
                    echo get_input_form("Card Holder Name", "card_holder_name");
                    echo get_input_form("Credit Card Number", "ccn");
                    echo get_input_form("Expiry Date(TODO: DatePicker)", "expiry_date");
                    echo get_input_form("CVV", "cvv");
                ?>
            </div>
            <div class="payment-right">
                <?php
                    echo get_form_btn("Add Credit Card");
                ?>
            </div>

            <div class="booking-pts">
                <h6> Booking Points </h6>
                <hr>
                <p>Currently you have TODO booking points</p>
            </div>
        </div>

        <div class="promotion-settings">
            <h2> Promotion Cards </h2>
            <hr>
            <p>TODO</p>
        </div>

        <div class="account-settings">
            <h2> Account Details </h2>
            <hr>
            
            <p>TODO: fetch username and email</p>
            <?php
                echo get_form_btn("Change e-mail");
                echo get_form_btn("Change password");
            ?>
            
        </div>


        <?php
         echo get_footer();
        ?>



   </body>


</html>