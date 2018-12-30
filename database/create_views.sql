USE ibitur_db;


-- Dependent Counts

DROP VIEW IF EXISTS DependentCounts;
DROP VIEW IF EXISTS AllzeroDependentCounts;
DROP VIEW IF EXISTS NonzeroDependentCounts;

CREATE VIEW NonzeroDependentCounts AS (
    SELECT COUNT(dependent_ID) AS dependent_count, reservation_ID
    FROM IncludedDependents
    GROUP BY (reservation_ID)
);

CREATE VIEW AllzeroDependentCounts AS (
    SELECT 0 AS dependent_count, ID AS reservation_ID
    FROM Reservation
    WHERE Reservation.ID NOT IN (SELECT reservation_ID FROM NonzeroDependentCounts)
);

CREATE VIEW DependentCounts AS
   (SELECT * FROM NonzeroDependentCounts)
   UNION 
   (SELECT * FROM AllzeroDependentCounts);

   
-- Tour Preview

DROP VIEW IF EXISTS TourPreview;
DROP VIEW IF EXISTS TourInterval;
DROP VIEW IF EXISTS TourDates;
DROP VIEW IF EXISTS TourSubDates5;
DROP VIEW IF EXISTS TourSubDates4;
DROP VIEW IF EXISTS TourSubDates3;
DROP VIEW IF EXISTS TourSubDates2;
DROP VIEW IF EXISTS TourSubDates1;
DROP VIEW IF EXISTS ReservationCounts;
DROP VIEW IF EXISTS AllzeroReservationCounts;
DROP VIEW IF EXISTS NonzeroReservationCounts;
DROP VIEW IF EXISTS NonCancelledReservation;

CREATE VIEW NonCancelledReservation AS
    (SELECT * FROM Reservation WHERE cancel_date IS NULL);

CREATE VIEW NonzeroReservationCounts AS
    (SELECT tour_ID, COUNT(customer_ID) AS resv_no
    FROM NonCancelledReservation GROUP BY tour_ID);

CREATE VIEW AllzeroReservationCounts AS (
    SELECT ID AS tour_ID, 0 AS resv_no
    FROM Tour
    WHERE Tour.ID NOT IN (SELECT tour_ID FROM NonzeroReservationCounts)
);

CREATE VIEW ReservationCounts AS
   (SELECT * FROM NonzeroReservationCounts)
   UNION 
   (SELECT * FROM AllzeroReservationCounts);

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
    FROM Tour, ReservationCounts, TourInterval
    WHERE Tour.ID = ReservationCounts.tour_ID AND Tour.ID = TourInterval.tour_ID);
