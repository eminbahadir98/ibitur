<?php
include("util/visuals.php");
include("util/session.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = "";	
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

            /*if(!is_valid_days(start_date,end_date)){
                showError("For Tour: End date cannot be before the start date");
                return false;
            }*/

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


        var day_base = "day";
        var day_row = 1;

        function createTourDay() {

            var start_date = document.forms["add-tour"]["start_date"].value;
            var end_date = document.forms["add-tour"]["end_date"].value;
            var tour_day      = document.forms["add-tour"]["tour_day"].value;
            var tour_day_desc = document.forms["add-tour"]["tour_day_desc"].value;
            //var included_loc1 = document.forms["add-tour"]["included_loc1"].options[document.forms["add-tour"]["included_loc1"].selectedIndex].text;
            //var included_loc2 = document.forms["add-tour"]["included_loc2"].options[document.forms["add-tour"]["included_loc2"].selectedIndex].text;
            if (tour_day) {
                /*if(!is_valid_days(start_date,tour_day) || !is_valid_days(tour_day,end_date)){
                    showError("Tour days must be between the start date and end date included");
                    return false;
                }*/

                if (checkBaseInfo("add-tour")) {
                    day_counter++;

                    var param_name = day_base + day_row + "[]";

                    // var numOfDay = daysBetween(start_date, tour_day);
                    document.getElementById("tour-days").innerHTML += "<tr id='" + day_counter + "'>\
                    <td> " + getHidden(param_name, day_row) + day_row + "</td>\
                    <td>" + getHidden(param_name, tour_day)  + formattedDate(tour_day) + "</td>\
                    <td> " + getHidden(param_name, tour_day_desc)  + tour_day_desc + "</td>\
                    <td>" + "<button class='submit-button btn' type='button' onclick='removeField(" + day_counter + ")'>Remove</button></td>\
                    </tr>";

                    document.getElementById("day_row").value = day_row;
                    day_row++;

                }
            }
            else
            {
                showError("For Tour day: Please enter a tour day to proceed");
                return false;
            }
        }




        var accom_base = "accom";
        var accom_row = 1;


        function createAccom(){
            var start_accom_date = document.forms["add-tour"]["start_accom_date"].value;
            var end_accom_date   = document.forms["add-tour"]["end_accom_date"].value;
            var hotel            = document.forms["add-tour"]["hotel"].options[document.forms["add-tour"]["hotel"].selectedIndex].text;
            var hotel_id            = document.forms["add-tour"]["hotel"].options[document.forms["add-tour"]["hotel"].selectedIndex].value;
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
                var param_name = accom_base + accom_row + "[]";

                document.getElementById("accoms").innerHTML += "<tr id='" + accom_counter + "'>\
                <td>" + getHidden(param_name, hotel_id) + hotel_id + "</td>\
                <td>" + getHidden(param_name, hotel) +hotel + "</td>\
                <td>" + getHidden(param_name, start_accom_date)+formattedDate(start_accom_date) + "</td>\
                <td>" + getHidden(param_name, end_accom_date) +formattedDate(end_accom_date)+ "</td>\
                <td>" + getHidden(param_name, rating) +rating + "</td>\
                <td>" + getHidden(param_name, address) + address + " </td>\
                <td>" + "<button class='submit-button btn' type='button' onclick='removeField(" + accom_counter + ")'>Remove</button></td>\
                </tr>";
                document.getElementById("accom_row").value = accom_row;
                accom_row++;
                console.log(document.getElementById("accoms").innerHTML);

            } else {
                showError("For Accommodations: Please enter an end date and start date to proceed");
                return false;
            }
        }

        function getHidden(name, val) {
            return "<input type='hidden' name='" + name + "' value='" + val + "' />";
        }

        var route_base = "route";
        var route_row = 1;
        
        function createTravelRoute() {
            var source_city         = document.forms["add-tour"]["source_city"].options[document.forms["add-tour"]["source_city"].selectedIndex].text;
            var dest_city           = document.forms["add-tour"]["dest_city"].options[document.forms["add-tour"]["dest_city"].selectedIndex].text;
            var source_city_id = document.forms["add-tour"]["source_city"].options[document.forms["add-tour"]["source_city"].selectedIndex].value;
            var dest_city_id = document.forms["add-tour"]["dest_city"].options[document.forms["add-tour"]["dest_city"].selectedIndex].value;
            var departure_date      = document.forms["add-tour"]["departure_date"].value;
            var departure_time      = document.forms["add-tour"]["departure_time"].value;
            var vehicle_type        = document.forms["add-tour"]["vehicle_type"].options[document.forms["add-tour"]["vehicle_type"].selectedIndex].text;
            var travel_company      = document.forms["add-tour"]["travel_company"].value;
            var departure_address   = document.forms["add-tour"]["departure_address"].value;
            var destination_address = document.forms["add-tour"]["destination_address"].value;

            if(departure_date && departure_time && departure_address && destination_address && travel_company){
                route_counter++;

                var param_name = route_base + route_row + "[]";

                document.getElementById("travel-routes").innerHTML += "<tr id='" + route_counter + "'>\
                <td> " + getHidden(param_name, source_city_id) + source_city +"</td>\
                <td> "+ getHidden(param_name, dest_city_id) + dest_city + "</td>\
                <td>"+ getHidden(param_name, vehicle_type) + vehicle_type +"</td>\
                <td>" + getHidden(param_name, travel_company)+ travel_company +"</td>\
                <td>" + getHidden(param_name, departure_date) + formattedDate(departure_date) + "</td>\
                <td>" + getHidden(param_name, departure_time)+ departure_time + "</td>\
                <td>" + getHidden(param_name, departure_address) + departure_address+ "</td>\
                <td>" + getHidden(param_name, destination_address) + destination_address + "</td>\
                <td>" + "<button class='submit-button btn' type='button' onclick='removeField(" + route_counter + ")'>Remove</button></td>\
                </tr>";

                document.getElementById("route_row").value = route_row;

                route_row++;

            } else
            {


                showError("For Travel Route: Please fill the empty places");
                return false;
            }


        }

        var event_base = "event";
        var event_row = 1;

        function createTripEvent() {
            
            var event_title       = document.forms["add-tour"]["event_title"].value;
            var event_date        = document.forms["add-tour"]["event_date"].value;
            var event_description = document.forms["add-tour"]["event_description"].value;
            var event_city =  document.forms["add-tour"]["event_city"].options[document.forms["add-tour"]["event_city"].selectedIndex].text;
            var event_city_id = document.forms["add-tour"]["event_city"].options[document.forms["add-tour"]["event_city"].selectedIndex].value;
            if(event_date && event_description && event_title){
                event_counter++;

                var param_name = event_base + event_row + "[]";

                document.getElementById("trip-events").innerHTML += "<tr id='" + event_counter + "'>\
                <td>" + getHidden(param_name, event_title) + event_title + "</td>\
                <td>" + getHidden(param_name, event_date) + formattedDate(event_date)+"</td>\
                <td>"+ getHidden(param_name, event_city_id) + event_city +"</td>\
                <td>"+ getHidden(param_name, event_description) + event_description +"</td>\
                <td>"+ "<button class='submit-button btn' type='button' onclick='removeField(" + event_counter + ")'>Remove</button></td>\
                </tr>";

                document.getElementById("event_row").value = event_row;

                event_row++;
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

<div class="inner-content">

    <h1>Create New Tour</h1>
    <hr>
    <br><br>
    
    <form name="add-tour" action="add_tour_post.php" method="post" >

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
            <input  class="form-control input-field" type="date" name="start_date" min=<?php echo date("Y-m-j")?>> <br><br>

            <label>End Date:</label>
            <input  class="form-control input-field" type="date" name="end_date" min=<?php echo date("Y-m-j")?>> <br><br>

            <label>Tour Tags:</label>
            <?php

                $get_tags_query = "SELECT * from Tag";

                $get_tags_result = mysqli_query($db, $get_tags_query);
                
                if($get_tags_result->num_rows == 0) {
                    echo "<p>There are no tags on the database yet.</p>";
                }
                else {
                    
                    
                    echo "
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                            <th>Tag</th>
                            <th>Use</th>
                            </tr>
                        </thead>
                        <tbody>
                    ";

                    $count = 0;
                    while($row = $get_tags_result->fetch_assoc()) {

                        $temp_check = "<input type='checkbox' name='checkbox[]' value=' " . $row['ID'] . "' /> ";

                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $temp_check . "</td>";
                        echo "</tr>";

                        $count = $count + 1;
                    }

                    echo "</tbody></table>";

                }
                ?>

            <hr>
        
            <h3 class="home-title">Tour Schedule</h3>

            <table class='table table-bordered' id="tour-days">

                <tr>
                    <th>Day</th>
                    <th>Tour Day Date</th>
                    <th>Description</th>
                    <th>Remove Tour Day</th>
                </tr>

            </table>

            <input type='hidden' id='day_row' name='day_row' value='0' />
            <hr>
            <br><br>

            <label>Date:</label>
            <input required class="form-control input-field" type="date" id="tour_day" name="tour_day" min=<?php echo date("Y-m-j")?>> <br><br>

            

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
            
            <table class='table table-bordered' id ="accoms">
                <tr>
                    <th>Accommodation Place</th>
                    <th>Number of day that will be stayed</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Rating</th>
                    <th>Address</th>
                    <th>Remove Accommodation</th>
                </tr>

            </table>
            <input type='hidden' id='accom_row' name='accom_row' value='0' />
            
            <hr>
            <br><br>

            <label>Accommodation:</label>
            <?php
            echo '<select class="form-control input-field" name="hotel" id="hotel">';
            $country_query = "SELECT address, star_rating, Hotel.ID as hid, Hotel.name as hname, City.name as cname FROM Hotel cross join City WHERE Hotel .city_ID = City.ID";
            $country_result = mysqli_query($db, $country_query);
            while ($row = $country_result->fetch_assoc()) {
                echo "<option value='" . $row['hid'] . "' name='" . $row["hname"] . "'>";

                echo $row["hname"].", ".$row["cname"].", ".$row["star_rating"].", Address:".$row["address"];
                echo '</option>';
            }
            echo '</select> <br><br>';
            ?>

            <br><br>
            <label>Start Date:</label>
            <input class="form-control input-field" type="date" id="start_accom_date" name="start_accom_date" min=<?php echo date("Y-m-j")?>> <br><br>
            <br><br>
            <label>End Date:</label>
            <input class="form-control input-field" type="date" id="end_accom_date" name="end_accom_date" min=<?php echo date("Y-m-j")?>> <br><br>
            <br><br>

            <button class="submit-button btn" type="button" onclick="createAccom()">Add New Accommodation</button>

    <!--</form>-->
<!--<form name="travel-routes-form" action="" method="post">-->
            <br><br>
            <hr>

            <h3 class="home-title">Travel Routes</h3>


            <table class='table table-bordered' id ="travel-routes">
                <tr>
                    <th>Source City</th>
                    <th>Destination City</th>
                    <th>Departure Time</th>
                    <th>Departure Address</th>
                    <th>Destination Address</th>
                    <th>Remove Travel Route</th>
                </tr>

            </table>
            <input type='hidden' id='route_row' name='route_row' value='0' />
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
            <input  class="form-control input-field" type="date" id="departure_date" name="departure_date" min=<?php echo date("Y-m-j")?>> <br><br>
            <br><br>

            <label>Departure Time:</label>
            <input  class="form-control input-field" type="text" id="departure_time" name="departure_time" > <br><br>
            <br><br>

            <label>Vehicle Type:</label>
            <select class="form-control input-field" name="vehicle_type" id="vehicle_type">
                <option>Plane</option>
                <option>Bus</option>
                <option>Cruise Ship</option>
                <option>Train</option>
            </select> <br><br>

            <label>Travel Company:</label>
            <input  class="form-control input-field" type="text" id="travel_company" name="travel_company" > <br><br>
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

            <table class='table table-bordered' id ="trip-events">
                <tr>
                    <th>Event Title</th>
                    <th>Event Date</th>
                    <th>Event City</th>
                    <th>Description</th>
                    <th>Remove Trip Event</th>
                </tr>
            </table>
            <input type='hidden' id='event_row' name='event_row' value='0' />
            <hr>
            <br><br>

            <label>Event Title:</label>
            <input  class="form-control input-field" type="text" id="event_title" name="tevent_title" > <br><br>
            <br><br>

            <label>Description:</label>
            <textarea class="form-control input-field" name="event_description" rows="5" cols="50" wrap="soft"> </textarea>
            <br><br>
            <br><br>
            <br><br>

            <label>Event Date:</label>
            <input  class="form-control input-field" type="date" id="event_date" name="event_date" min=<?php echo date("Y-m-j")?>> <br><br>
            <br><br>

            <label>Event City:</label>

            <?php
            echo '<select class="form-control input-field" name="event_city" id="event_city">';
            $country_query = "SELECT ID, name FROM City";
            $country_result = mysqli_query($db, $country_query);
            while ($row = $country_result->fetch_assoc()) {
                echo '<option value=';
                echo $row["ID"];
                echo "'>";
                echo $row["name"];
                echo '</option>';
            }
            echo '</select><br><br>';
            ?>

            <button class="submit-button btn" type="button" onclick="createTripEvent()">Add New Travel Route</button>

    <!--</form>-->

        <br><br><br>
        <hr>
        <br>
        <button class="right btn" type="submit" name="add-tour-submit" onclick="checkAllTourInfo()">Create Tour</button>
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
