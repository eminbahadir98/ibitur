USE ibitur_db;


DROP VIEW IF EXISTS TourPreview;
DROP VIEW IF EXISTS TourInterval;
DROP VIEW IF EXISTS TourDates;
DROP VIEW IF EXISTS TourSubDates5;
DROP VIEW IF EXISTS TourSubDates4;
DROP VIEW IF EXISTS TourSubDates3;
DROP VIEW IF EXISTS TourSubDates2;
DROP VIEW IF EXISTS TourSubDates1;
DROP VIEW IF EXISTS ReservationCount;
DROP VIEW IF EXISTS NonCancelledReservation;


CREATE VIEW NonCancelledReservation AS
    (SELECT * FROM Reservation WHERE cancel_date IS NULL);

CREATE VIEW ReservationCount AS
    (SELECT tour_ID, COUNT(customer_ID) AS resv_no
    FROM NonCancelledReservation GROUP BY tour_ID);

CREATE VIEW TourSubDates1 AS
    (SELECT tour_ID, enter_date AS the_date FROM Tour, Accommodation
    WHERE Tour.ID = Accommodation.tour_ID);

CREATE VIEW TourSubDates2 AS
    (SELECT tour_ID, exit_date AS the_date FROM Tour, Accommodation
    WHERE Tour.ID = Accommodation.tour_ID);

CREATE VIEW TourSubDates3 AS
    (SELECT tour_ID, dept_time AS the_date FROM Tour, TravelRoute
    WHERE Tour.ID = TravelRoute.tour_ID);

CREATE VIEW TourSubDates4 AS
    (SELECT tour_ID, arriv_time AS the_date FROM Tour, TravelRoute
    WHERE Tour.ID = TravelRoute.tour_ID);

CREATE VIEW TourSubDates5 AS
    (SELECT tour_ID, trip_date AS the_date FROM Tour, TripEvent
    WHERE Tour.ID = TripEvent.tour_ID);

CREATE VIEW TourDates AS 
    (SELECT DISTINCT * FROM TourSubDates1)
    UNION (SELECT DISTINCT * FROM TourSubDates2)
    UNION (SELECT DISTINCT * FROM TourSubDates3)
    UNION (SELECT DISTINCT * FROM TourSubDates4)
    UNION (SELECT DISTINCT * FROM TourSubDates5);

CREATE VIEW TourInterval AS
    (SELECT tour_ID, MIN(the_date) AS start_date, MAX(the_date) AS end_date
    FROM TourDates GROUP BY tour_ID);

CREATE VIEW TourPreview AS 
    (SELECT Tour.ID AS tour_ID, name, description, image_path, price, start_date, end_date,
    (quota - resv_no) AS remaining_quota
    FROM Tour, ReservationCount, TourInterval
    WHERE Tour.ID = ReservationCount.tour_ID AND Tour.ID = TourInterval.tour_ID);
