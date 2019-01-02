<?php
include("util/visuals.php");
include("util/session.php");


if(isset($_POST['add-tour-submit'])) {


    $title = $_POST['title'];
    $tour_desc = $_POST['tour_desc'];
    $tour_price = $_POST['tour_price'];
    $tour_quota = $_POST['tour_quota'];
    $cancelling_date = $_POST['cancelling_date'];
    
    $image_path = "./images/" . "img" . (round(microtime(true) * 1000)) . ".png";
    if (move_uploaded_file($_FILES["tour_image"]["tmp_name"], $image_path)) {
        echo "The file ". basename( $_FILES["tour_image"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $tour_query = "INSERT INTO TOUR(name, description, image_path, quota, price, creator_ID, cancelling_deadline)
    VALUES ( '$title', '$tour_desc', '$image_path', '$tour_quota', '$tour_price', '$current_id','$cancelling_date');";

    echo $tour_query;
    echo "<br>";
    $tour_result = mysqli_query($db, $tour_query);

    $tour_id = mysqli_insert_id($db);

    $row_cnt = $_POST['day_row'];

    for($i = 1; $i <= $row_cnt; $i++) {
        $arr_name = "day" . $i;
        $data = $_POST[$arr_name];

        $day_no = $data[0];
        $day_date = $data[1];
        $day_desc = $data[2];

        $tour_day_query = "INSERT INTO TourDay(day_no, day_date, description)
        VALUES ('$day_no', '$day_date', '$day_desc');";

        echo $tour_day_query;
        echo "<br>";
        $tour_day_result = mysqli_query($db, $tour_day_query);


        // DEBUG
        /*
        foreach($data as $item) {
            echo $item . "<br>";
        }*/
    }

    echo "<br>";

    $row_cnt = $_POST['accom_row'];

    for($i = 1; $i <= $row_cnt; $i++) {
        $arr_name = "accom" . $i;
        $data = $_POST[$arr_name];

        $hotel_id = $data[0];
        $accom_start_date = $data[2];
        $accom_end_date = $data[3];

        $accom_query = "INSERT INTO Accommodation(tour_ID, place_ID, enter_date, exit_date) VALUES('$tour_id', '$hotel_id',
        '$accom_start_date', '$accom_end_date');";

        echo $accom_query;
        echo "<br>";
        $accom_result = mysqli_query($db, $accom_query);

        // DEBUG
        /*
        foreach($data as $item) {
            echo $item . "<br>";
        }*/
    }

    echo "<br>";

    $row_cnt = $_POST['route_row'];

    for($i = 1; $i <= $row_cnt; $i++) {
        $arr_name = "route" . $i;
        $data = $_POST[$arr_name];


        $source_city = $data[0];
        $dest_city = $data[1];
        $vehicle_type = $data[2];
        $travel_company = $data[3];
        $departure_date =$data[4];
        $departure_address=$data[6];
        $destination_address=$data[7];

        $route_query = "INSERT INTO TravelRoute(vehicle_type, company_name, tour_ID, from_city_ID, to_city_ID, dept_address, dept_time, arriv_address, arriv_time) VALUES ('$vehicle_type', '$travel_company', '$tour_id', '$source_city', '$dest_city', '$departure_address',
        '$departure_date', '$destination_address', '$departure_date');";
        

        echo $route_query;
        echo "<br>";
        $route_result = mysqli_query($db, $route_query);
        // DEBUG
        /*
        foreach($data as $item) {
            echo $item . "<br>";
        }*/
    }

    echo "<br>";

    $row_cnt = $_POST['event_row'];

    for($i = 1; $i <= $row_cnt; $i++) {
        $arr_name = "event" . $i;
        $data = $_POST[$arr_name];

        $event_name = $data[0];
        $event_date = $data[1];
        $event_city = $data[2];
        $event_desc = $data[3];
        
        $event_query = "INSERT INTO TripEvent(tour_ID, city_ID, name, description, trip_date) VALUES('$tour_id', '$event_city',
        '$event_name', '$event_desc', '$event_date');";

        echo $event_query;
        echo "<br>";
        $event_result = mysqli_query($db, $event_query);
        // DEBUG;
        /*
        foreach($data as $item) {
            echo $item . "<br>";
        }*/
    }




}


?>