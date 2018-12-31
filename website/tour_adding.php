<?php
include("util/visuals.php");
include("util/session.php");

$error = "";

$accom_addres = array();


?>

<html>

<head>
    <title>IBITUR - Register</title>
    <link rel="stylesheet" href="style/style.css"/>
    <link rel="stylesheet" href="lib/bootstrap.min.css"/>

    <script type="text/javascript">



        var start_date;
        var tour_day;
        var tour_day_desc;
        var included_loc1;
        var included_loc2;

        var accom_counter = 0;
        var day_counter = 0;

        function is_url(str)
        {
            regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            if (regexp.test(str))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        function is_valid_days(start, end) {

            var d2 = Date.parse(start);
            var d3 = Date.parse(end);
            if( d3 < d2){
                return false;
            }
                return true;
        }
        function showError(message) {
            var errorDiv = document.getElementById("error-div");
            errorDiv.innerHTML =
                "<div class='alert alert-warning' role='alert'>" +
                message +
                "</div>";
        }

        function checkInput() {
            var title = document.forms["register-form"]["title"].value;
            var tour_desc = document.forms["register-form"]["tour_desc"].value;
            var image_path = document.forms["register-form"]["image_path"].value;
            var tour_price = document.forms["register-form"]["tour_price"].value;
            var tour_quota = document.forms["register-form"]["tour_quota"].value;

            var cancelling_date = document.forms["register-form"]["cancelling_date"].value;
            var start_date = document.forms["register-form"]["start_date"].value;
            var end_date = document.forms["register-form"]["end_date"].value;

            var tour_day = document.forms["register-form"]["tour_day"].value;
            var tour_day_desc = document.forms["register-form"]["tour_day_desc"].value;

            if (title.length < 3) {
                showError("Title should contain minimum of 3 characters.");
                return false;
            }
            if (tour_desc.length < 20) {
                showError("Tour description should contain minimum of 20 characters.");
                return false;
            }
            if (!is_url(image_path)) {
                showError("Image path is not valid");
                return false;
            }
            if (Number(tour_price) < 1) {
                showError("Price must be positive");
                return false;
            }
            if (Number(tour_quota) < 1) {
                showError("Quota must be positive");
                return false;
            }

            if(!is_valid_days(start_date,end_date)){
                showError("For Tour: End date cannot be before the start date");
                return false;
            }
            if(!is_valid_days(start_date,tour_day) || !is_valid_days(tour_day,end_date)){
                showError("Tour day must be between the start date and end date included");
                return false;
            }
            if (tour_day_desc.length < 20) {
                showError("Tour day description contain minimum of 20 characters.");
                return false;
            }


                return true;
        }
        function treatAsUTC(date) {
            var result = new Date(date);
            result.setMinutes(result.getMinutes() - result.getTimezoneOffset());
            return result;
        }

        function daysBetween(startDate, endDate) {
            var millisecondsPerDay = 24 * 60 * 60 * 1000;
            return ((treatAsUTC(endDate) - treatAsUTC(startDate)) / millisecondsPerDay)+1;
        }
        function removeField(id) {
            document.getElementById(id).remove();
        }


        function createTourDay()
        {

            start_date = document.forms["register-form"]["start_date"].value;
            tour_day = document.forms["register-form"]["tour_day"].value;
            tour_day_desc = document.forms["register-form"]["tour_day_desc"].value;
            included_loc1 = document.forms["register-form"]["included_loc1"].options[document.forms["register-form"]["included_loc1"].selectedIndex].text;
            included_loc2 = document.forms["register-form"]["included_loc2"].options[document.forms["register-form"]["included_loc2"].selectedIndex].text;

            if(checkInput())
            {
                day_counter++;
                var numOfDay = daysBetween(start_date, tour_day) ;
                document.getElementById("tour-days").innerHTML += "<div id='"+ day_counter + "'><br><br><p> Day "+ numOfDay + " - "+included_loc1+" - "+included_loc2+"</p><br><p> "+tour_day_desc +"</p><br><br>"
            + "<button class='submit-button btn' type='button' onclick='removeField("+day_counter+")'>Remove</button><br><br></div>";

            }
        }


        function createAccom(){
            var start_accom_date = document.forms["accom-form"]["start_accom_date"].value;
            var end_accom_date = document.forms["accom-form"]["end_accom_date"].value;
            var hotel = document.forms["accom-form"]["hotel"].options[document.forms["accom-form"]["hotel"].selectedIndex].text;
            var rating = hotel.split(", ")[2];
            var address = hotel.split(", ").slice(3,hotel.split(", ").length);
            hotel = hotel.split(", ")[0] + ", " + hotel.split(", ")[1];

            if(!is_valid_days(start_accom_date,end_accom_date)){
                showError("For Accommodations: End date cannot be before the start date");
                return false;
            }

            if(checkInput()){
                var numOfDay = daysBetween(start_accom_date, end_accom_date) ;
                accom_counter++;
                document.getElementById("accoms").innerHTML += "<div id='"+accom_counter+"'><br><br><p> "+ hotel + " Number of day that will be stayed: "+numOfDay +"</p><br><p>Rating: " + rating +"</p><br><p>"+address+" </p> <br><br>"
                    + "<button class='submit-button btn' type='button' onclick='removeField("+accom_counter+")'>Remove</button><br><br></div>";

            }


        }



    </script>

</head>

<body class="content">

<?php
if ($logged_in) {
    echo get_header($current_fullname);
} else {
    header("location: login.php");
}
?>

<h1 class="home-title">Add New Tour</h1>
<div class="register-div">
    <form name="register-form"  action="" method="post">
        <label>Title:</label>
        <input required class="form-control input-field" type="text" name="title"/> <br><br>
        <label>Description:</label>
        <input required class="form-control input-field" type="text" name="tour_desc"/> <br><br>
        <label>Tour Image:</label>
        <input required class="form-control input-field" type="text" name="image_path"/> <br><br>
        <label>Tour Price:</label>
        <input required class="form-control input-field" type="text" name="tour_price"/> <br><br>

        <label>Quota:</label>
        <input required class="form-control input-field" type="text" name="tour_quota"/> <br><br>

        <label>Cancelling Date:</label>
        <input required class="form-control input-field" type="date" name="cancelling_date" min=<?php echo date("Y-m-j")?>> <br><br>

        <label>Start Date:</label>
        <input required class="form-control input-field" type="date" name="start_date" min=<?php echo date("Y-m-j")?>> <br><br>

        <label>End Date:</label>
        <input required class="form-control input-field" type="date" name="end_date" min=<?php echo date("Y-m-j")?>> <br><br>

        <hr>

        <h3 class="home-title">Tour Schedule</h3>

        <div id="tour-days">

        </div>

        <label>Date:</label>
        <input required class="form-control input-field" type="date" id="tour_day" name="tour_day" min=<?php echo date("Y-m-j")?>> <br><br>

        <label>Included Locations:</label>
        <?php
        echo '<select class="form-control input-field" name="included_loc1">';
        $country_query = "SELECT ID, name FROM City";
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
        <br>
        <?php
        echo '<select class="form-control input-field" name="included_loc2">';
        $country_query = "SELECT ID, name FROM City";
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

        <label>Decription:</label>
        <textarea class="form-control input-field" name="tour_day_desc" rows="5" cols="50" wrap="soft"> </textarea>
        <br><br>
        <br><br>
        <br><br>
        <button class="submit-button btn" type="button" onclick="createTourDay()">Add New Schedule Item</button>
    </form>

    <form name="accom-form" action="" method="post">
        <br><br>
        <hr>
        <div id ="accoms">

        </div>

        <label>Accommodation:</label>
        <?php
        echo '<select class="form-control input-field" name="hotel" id="hotel">';
        $country_query = "SELECT address, star_rating, Hotel.ID as hid, Hotel.name as hname, City.name as cname FROM Hotel cross join City WHERE Hotel .city_ID = City.ID";
        $country_result = mysqli_query($db, $country_query);
        while ($row = $country_result->fetch_assoc()) {
            echo '<option value=';
            echo $row["hid"];
            echo " name=";
            echo $row["address"];
            echo ">";
            echo $row["hname"].", ".$row["cname"].", ".$row["star_rating"].", ".$row["address"];
            echo '</option>';
        }
        echo '</select> <br><br>';
        ?>

        <br><br>
        <label>Start Date:</label>
        <input required class="form-control input-field" type="date" id="start_accom_date" name="start_accom_date" min=<?php echo date("Y-m-j")?>> <br><br>
        <br><br>
        <label>End Date:</label>
        <input required class="form-control input-field" type="date" id="end_accom_date" name="end_accom_date" min=<?php echo date("Y-m-j")?>> <br><br>
        <br><br>

        <button class="submit-button btn" type="button" onclick="createAccom()">Add New Accommodation</button>

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

</body>

</html>
