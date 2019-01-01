<?php
include("util/visuals.php");
include("util/session.php");

$error = "";

/**
 * This part create a tour and add only one tour day and accommodation to the tour by getting input from the fields.
 * But it cannot parse the multiple table elements to add multiple tours
 */
/*if($_SERVER["REQUEST_METHOD"] == "POST") {
    $dom =new Dom

    $title     = mysqli_real_escape_string($db, $_POST['title']);
    $tour_desc = mysqli_real_escape_string($db, $_POST['tour_desc']);
    $image_path= mysqli_real_escape_string($db, $_POST['image_path']);
    $tour_tag  = mysqli_real_escape_string($db, $_POST['tour_tag']);
    $tour_price= mysqli_real_escape_string($db, $_POST['tour_price']);
    $tour_quota= mysqli_real_escape_string($db, $_POST['tour_quota']);

    $cancelling_date =mysqli_real_escape_string($db, $_POST["cancelling_date"]);
    $start_date =mysqli_real_escape_string($db, $_POST["start_date"]);
    $end_date = mysqli_real_escape_string($db, $_POST["end_date"]);

    $tour_day      = mysqli_real_escape_string($db, $_POST["tour_day"]);
    $tour_day_desc = mysqli_real_escape_string($db, $_POST["tour_day_desc"]);
    $included_loc1 = mysqli_real_escape_string($db, $_POST["included_loc1"]);
    $included_loc2 = mysqli_real_escape_string($db, $_POST["included_loc2"]);


    $start_accom_date =  mysqli_real_escape_string($db, $_POST["start_accom_date"]);
    $end_accom_date   =  mysqli_real_escape_string($db, $_POST["end_accom_date"]);
    $hotel            =  mysqli_real_escape_string($db, $_POST["hotel"]);
    $hotel_address_pos = strpos($hotel, "Address:") + 8;
    $hotel_address = substr($hotel, $hotel_address_pos, strlen($hotel)-1);
    $source_city         =  mysqli_real_escape_string($db, $_POST["source-city"]);
    $dest_city           =  mysqli_real_escape_string($db, $_POST["dest-city"]);
    $departure_date      =  mysqli_real_escape_string($db, $_POST["departure_date"]);
    $departure_time      =  mysqli_real_escape_string($db, $_POST["departure_time"]);
    $vehicle_type        =  mysqli_real_escape_string($db, $_POST["vehicle-type"]);
    $travel_company      =  mysqli_real_escape_string($db, $_POST["travel_company"]);
    $departure_address   =  mysqli_real_escape_string($db, $_POST["departure_address"]);
    $destination_address =  mysqli_real_escape_string($db, $_POST["destination_address"]);

    $event_title       = mysqli_real_escape_string($db, $_POST["event_title"]);
    $event_date        = mysqli_real_escape_string($db, $_POST["event_date"]);
    $event_description = mysqli_real_escape_string($db, $_POST["event_description"]);
    $event_city =  mysqli_real_escape_string($db, $_POST["event_city"]);

    $check_query = "SELECT name FROM Tour WHERE name='$title'";
    $check_result = mysqli_query($db, $check_query);
    $title_exists = true;
    if (mysqli_num_rows($check_result) == 0) {
        $title_exists = false;
    }
    $addTour_succeed = false;
    $addTourDay_succeed = false;
    $addTourAccom_succeed = false;
    $addTourRoute_succeed = false;
    $addTourEvent_succeed = false;
    $tour_id = null;
    if(!$title_exists){
        $addTour_subquery = "INSERT INTO Tour(ID, name, description, image_path, quota, price, creator_ID, cancelling_deadline)
        VALUES(LAST_INSERT_ID(),'$title', '$tour_desc', '$image_path', '$tour_quota',
        '$pric_price', '$current_id', '$cancelling_date')";
        $addTour_succeed =mysqli_query($db, $addTour_subquery);
        $tour_id = mysqli_query($db, "SELECT ID FROM Tour WHERE name='$title'");
    }
    if($addTour_succed && $tour_id){
        $ddTourDay_subquery = "INSERT INTO TourDay(tour_ID, day_no, day_date, description)
        VALUES('$tour_id','$tour_day', '$tour_desc', '$image_path')";
        $addTourDay_succeed = mysqli_query($db, $addTourDay_subquery);
    }
    if($addTour_succed && $tour_id){
        $place_id = mysqli_query($db, "SELECT ID FROM Hotel where address= '$hotel_address' ");
        if($place_id) {
            $ddTourAccom_subquery = "INSERT INTO Accommodation(ID,tour_ID, place_ID, enter_date, exit_date)
        VALUES(LAST_INSERT_ID(),'$tour_id','$place_id', '$start_accom_date', '$end_accom_date')";
        }
    }


}*/


?>

<html>
<?php
if ($logged_in && $current_is_staff) {
    echo get_header($current_fullname, true);
} else {
    header("location: login.php");
}
?>
<head>
    <title>IBITUR - Tour Adding</title>
    <link rel="stylesheet" href="style/style.css"/>
    <link rel="stylesheet" href="lib/bootstrap.min.css"/>

    <script type="text/javascript">

        var accom_counter = 100;
        var day_counter   = 200;
        var route_counter = 300;
        var event_counter = 400;

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

        function checkBaseInfo() {
            var title      = document.forms["add-tour"]["title"].value;
            var tour_desc  = document.forms["add-tour"]["tour_desc"].value;
            var image_path = document.forms["add-tour"]["image_path"].value;
            var tour_tag = document.forms["add-tour"]["tour_tag"].value;
            var tour_price = document.forms["add-tour"]["tour_price"].value;
            var tour_quota = document.forms["add-tour"]["tour_quota"].value;

            var cancelling_date = document.forms["add-tour"]["cancelling_date"].value;
            var start_date = document.forms["add-tour"]["start_date"].value;
            var end_date = document.forms["add-tour"]["end_date"].value;

            var tour_desc = document.forms["add-tour"]["tour_desc"].value;


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

            if (tour_desc.length < 20) {
                showError("Tour description contain minimum of 20 characters.");
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
            if(id < 200)
                accom_counter--;
            else if(id <300)
                day_counter--;
            else if(id <400)
                route_counter--;
            else
                event_counter--;

            document.getElementById(id).remove();
        }

        function formattedDate(date) {
            var d = new Date(date || Date.now()),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [day, month, year].join('/');
        }


        function createTourDay() {

            var start_date = document.forms["add-tour"]["start_date"].value;
            var end_date = document.forms["add-tour"]["end_date"].value;
            var tour_day      = document.forms["add-tour"]["tour_day"].value;
            var tour_day_desc = document.forms["add-tour"]["tour_day_desc"].value;
            var included_loc1 = document.forms["add-tour"]["included_loc1"].options[document.forms["add-tour"]["included_loc1"].selectedIndex].text;
            var included_loc2 = document.forms["add-tour"]["included_loc2"].options[document.forms["add-tour"]["included_loc2"].selectedIndex].text;
            if (tour_day) {
                if(!is_valid_days(start_date,tour_day) || !is_valid_days(tour_day,end_date)){
                    showError("Tour days must be between the start date and end date included");
                    return false;
                }

                if (checkBaseInfo("add-tour")) {
                    day_counter++;
                    var numOfDay = daysBetween(start_date, tour_day);
                    document.getElementById("tour-days").innerHTML += "<td id='" + day_counter + "'><br><br><p> Day " + numOfDay + " - " + included_loc1 + " - " + included_loc2 + "</p><br><p> Tour Day: " + formattedDate(tour_day) + "</p><br><p> " + tour_day_desc + "</p><br><br>"
                        + "<button class='submit-button btn' type='button' onclick='removeField(" + day_counter + ")'>Remove</button><br><br></td>";

                }
            }
            else
            {
                showError("For Tour day: Please enter a tour day to proceed");
                return false;
            }
        }






        function createAccom(){
            var start_accom_date = document.forms["add-tour"]["start_accom_date"].value;
            var end_accom_date   = document.forms["add-tour"]["end_accom_date"].value;
            var hotel            = document.forms["add-tour"]["hotel"].options[document.forms["add-tour"]["hotel"].selectedIndex].text;
            var rating = hotel.split(", ")[2];
            var address = hotel.split(", ").slice(3,hotel.split(", ").length);
            hotel = hotel.split(", ")[0] + ", " + hotel.split(", ")[1];
            if(start_accom_date && end_accom_date) {
                if (!is_valid_days(start_accom_date, end_accom_date)) {
                    showError("For Accommodations: End date cannot be before the start date");
                    return false;
                }


                var numOfDay = daysBetween(start_accom_date, end_accom_date);
                accom_counter++;
                document.getElementById("accoms").innerHTML += "<td id='" + accom_counter + "'><br><br><p> " + hotel + "</p><br><p> Number of day that will be stayed: " + numOfDay + "</p><br><p> Start Day: " + formattedDate(start_accom_date) + "</p><br><p> End Day: " + formattedDate(end_accom_date)+
                    "</p><br><p>Rating: " + rating + "</p><br><p>" + address + " </p> <br><br>"
                    + "<button class='submit-button btn' type='button' onclick='removeField(" + accom_counter + ")'>Remove</button><br><br></td>";



            } else {
                showError("For Accommodations: Please enter an end date and start date to proceed");
                return false;
            }
        }

        function createTravelRoute() {
            var source_city         = document.forms["add-tour"]["source_city"].options[document.forms["add-tour"]["source_city"].selectedIndex].text;
            var dest_city           = document.forms["add-tour"]["dest_city"].options[document.forms["add-tour"]["dest_city"].selectedIndex].text;
            var departure_date      = document.forms["add-tour"]["departure_date"].value;
            var departure_time      = document.forms["add-tour"]["departure_time"].value;
            var vehicle_type        = document.forms["add-tour"]["vehicle_type"].options[document.forms["add-tour"]["vehicle_type"].selectedIndex].text;
            var travel_company      = document.forms["add-tour"]["travel_company"].value;
            var departure_address   = document.forms["add-tour"]["departure_address"].value;
            var destination_address = document.forms["add-tour"]["destination_address"].value;

            if(departure_date && departure_time && departure_address && destination_address && travel_company){
                route_counter++;

                document.getElementById("travel-routes").innerHTML += "<td id='" + route_counter + "'><br><br><p> " + source_city +" --> "+ dest_city + " ("+ vehicle_type +",  " + travel_company+" )"+"</p><br><p>Departure Date: " + formattedDate(departure_date) + "</p><br><p> Departure Time: " + departure_time + "</p><br><p> Departure address: " + departure_address+ "</p><br><p> Destination address: " + destination_address +
                    "<button class='submit-button btn' type='button' onclick='removeField(" + route_counter + ")'>Remove</button><br><br></td>";
            } else
            {


                showError("For Travel Route: Please fill the empty places");
                return false;
            }


        }

        function createTripEvent() {
            var event_title       = document.forms["add-tour"]["event_title"].value;
            var event_date        = document.forms["add-tour"]["event_date"].value;
            var event_description = document.forms["add-tour"]["event_description"].value;
            var event_city =  document.forms["add-tour"]["event_city"].options[document.forms["add-tour"]["event_city"].selectedIndex].text;
            if(event_date && event_description && event_title){
                event_counter++;

                document.getElementById("trip-events").innerHTML += "<td id='" + event_counter + "'><br><br><p><u> " + event_title + "</u>  ( "+ formattedDate(event_date)+" ) </p><br><p> "+ event_city +"</p><br><p>"+ + event_description +"</p><br><p>"+
                    "<button class='submit-button btn' type='button' onclick='removeField(" + event_counter + ")'>Remove</button><br><br></td>";
            } else
            {
                showError("For Trip Events: Please fill the empty places");
                return false;
            }

        }
        function checkAllTourInfo() {

            if(!checkBaseInfo()){
                return false;
            }
            if(accom_counter !== 100 && day_counter !== 200 && route_counter !== 300&& event_counter !== 400) {
                return true;
            }else {
                showError("Please add at least one element for all types");
                return false;
            }

        }



    </script>

</head>

<body class="content">



<h1 class="home-title">Add New Tour</h1>
<div class="register-div">

    <form name="add-tour" action="" method="post" >

        <!--<form name="register-form"  action="" method="post">-->
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

        <label>Tour Tags:</label>
        <select class="form-control input-field" name="vehicle-type" id="tour_tag">
            <option>Vegan</option>
            <option>Vegetarian</option>
        </select> <br><br>

        <hr>

        <h3 class="home-title">Tour Schedule</h3>

        <table id="tour-adding">
            <tr id="tour-days">

            </tr>

        </table>
        <hr>
        <br><br>

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
        <button class="submit-button btn" type="button" onclick="createTourDay('add-tour')">Add New Schedule Item</button>
        <!--</form>-->

        <!--<form name="accom-form" action="" method="post">-->
        <br><br>
        <hr>

        <h3 class="home-title">Accommodations</h3>

        <table id="tour-adding">
            <tr id ="accoms">

            </tr>

        </table>
        <hr>
        <br><br>

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
            echo $row["hname"].", ".$row["cname"].", ".$row["star_rating"].", Address:".$row["address"];
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

        <!--</form>-->
        <!--<form name="travel-routes-form" action="" method="post">-->
        <br><br>
        <hr>

        <h3 class="home-title">Travel Routes</h3>


        <table id="tour-adding">
            <tr id ="travel-routes">

            </tr>

        </table>
        <hr>
        <br><br>

        <label>Source City:</label>

        <?php
        echo '<select class="form-control input-field" name="source_city" id="source_city">';
        $country_query = "SELECT ID, name FROM City";
        $country_result = mysqli_query($db, $country_query);
        while ($row = $country_result->fetch_assoc()) {
            echo '<option value=';
            echo $row["ID"];
            echo ">";
            echo $row["name"];
            echo '</option>';
        }
        echo '</select><br><br>';
        ?>

        <label>Destination City:</label>

        <?php
        echo '<select class="form-control input-field" name="dest_city" id="dest_city">';
        $country_query = "SELECT ID, name FROM City";
        $country_result = mysqli_query($db, $country_query);
        while ($row = $country_result->fetch_assoc()) {
            echo '<option value=';
            echo $row["ID"];
            echo ">";
            echo $row["name"];
            echo '</option>';
        }
        echo '</select><br><br>';
        ?>

        <br><br>
        <label>Departure Date:</label>
        <input required class="form-control input-field" type="date" id="departure_date" name="departure_date" min=<?php echo date("Y-m-j")?>> <br><br>
        <br><br>

        <label>Departure Time:</label>
        <input required class="form-control input-field" type="text" id="departure_time" name="departure_time" > <br><br>
        <br><br>

        <label>Vehicle Type:</label>
        <select class="form-control input-field" name="vehicle_type" id="vehicle_type">
            <option>Plane</option>
            <option>Bus</option>
            <option>Cruise Ship</option>
            <option>Train</option>
        </select> <br><br>

        <label>Travel Company:</label>
        <input required class="form-control input-field" type="text" id="travel_company" name="travel_company" > <br><br>
        <br><br>


        <label>Departure Address:</label>
        <textarea class="form-control input-field" name="departure_address" rows="5" cols="50" wrap="soft"> </textarea>
        <br><br>
        <br><br>
        <br><br>

        <label>Destination Address:</label>
        <textarea class="form-control input-field" name="destination_address" rows="5" cols="50" wrap="soft"> </textarea>
        <br><br>
        <br><br>
        <br><br>

        <button class="submit-button btn" type="button" onclick="createTravelRoute()">Add New Travel Route</button>

        <!-- </form>-->

        <!-- <form name="trip-events-form" action="" method="post">-->
        <br><br>
        <hr>

        <h3 class="home-title">Trip Events</h3>

        <table id="tour-adding">
            <tr id ="trip-events">

            </tr>
        </table>

        <hr>
        <br><br>

        <label>Event Title:</label>
        <input required class="form-control input-field" type="text" id="event_title" name="tevent_title" > <br><br>
        <br><br>

        <label>Description:</label>
        <textarea class="form-control input-field" name="event_description" rows="5" cols="50" wrap="soft"> </textarea>
        <br><br>
        <br><br>
        <br><br>

        <label>Event Date:</label>
        <input required class="form-control input-field" type="date" id="event_date" name="event_date" min=<?php echo date("Y-m-j")?>> <br><br>
        <br><br>

        <label>Event City:</label>

        <?php
        echo '<select class="form-control input-field" name="event_city" id="event_city">';
        $country_query = "SELECT ID, name FROM City";
        $country_result = mysqli_query($db, $country_query);
        while ($row = $country_result->fetch_assoc()) {
            echo '<option value=';
            echo $row["ID"];
            echo ">";
            echo $row["name"];
            echo '</option>';
        }
        echo '</select><br><br>';
        ?>

        <button class="submit-button btn" type="button" onclick="createTripEvent()">Add New Travel Route</button>

        <!--</form>-->

        <br>
        <hr>
        <br>
        <button class="right btn" type="button" onclick="checkAllTourInfo()">Add Tour</button>
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
<div>
    <?php
    echo get_footer();
    ?>
</div>

</body>

</html>
