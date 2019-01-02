USE ibitur_db;


INSERT INTO Country(name) VALUES("Turkey");
INSERT INTO Country(name) VALUES("England");
INSERT INTO Country(name) VALUES("Sweden");
INSERT INTO Country(name) VALUES("Germany");
INSERT INTO Country(name) VALUES("United States");
INSERT INTO Country(name) VALUES("Japan");

INSERT INTO City(name, country_ID) VALUES("Ankara", (SELECT ID FROM Country WHERE name="Turkey"));
INSERT INTO City(name, country_ID) VALUES("Malatya", (SELECT ID FROM Country WHERE name="Turkey"));
INSERT INTO City(name, country_ID) VALUES("Antalya", (SELECT ID FROM Country WHERE name="Turkey"));
INSERT INTO City(name, country_ID) VALUES("Stockholm", (SELECT ID FROM Country WHERE name="Sweden"));
INSERT INTO City(name, country_ID) VALUES("Tokyo", (SELECT ID FROM Country WHERE name="Japan"));

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("bahadir", "bahadir@example.com", "123", "Emin", "Bahadir", "Tuluce");
INSERT INTO CustomerAccount(ID, national_ID, nationality, gender, date_of_birth)
    VALUES(LAST_INSERT_ID(), "12345678912", (SELECT ID FROM Country WHERE name="Turkey"), "Male", "1998-02-18");

INSERT INTO CustomerTelephones(customer_ID, telephone_no)
    VALUES ((SELECT ID FROM Account WHERE username="bahadir"), '0530999999');

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("sami", "sami@example.com", "123", "Mahmud", "Sami", "Aydin");
INSERT INTO CustomerAccount(ID, national_ID, nationality, gender, date_of_birth)
    VALUES(LAST_INSERT_ID(), "141434141", (SELECT ID FROM Country WHERE name="Turkey"), "Male", "1997-01-01");
    
INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("abdullah", "abdullah@example.com", "123", "Abdullah", NULL, "Talayhan");
INSERT INTO StaffAccount(ID) VALUES(LAST_INSERT_ID());

INSERT INTO Tour(name, description, image_path, quota, price, creator_ID)
    VALUES("Anatolia Tour", "This is an anatolia tour...", "./images/anatolia.png", 5, 395.90,
    (SELECT ID FROM Account WHERE username="abdullah"));
    
INSERT INTO Hotel(city_ID, name, address, star_rating)
    VALUES((SELECT ID FROM City WHERE name="Antalya"), "Sunflower Hotel", "Some street, some road...", 4);

INSERT INTO Accommodation(tour_ID, place_ID, enter_date, exit_date)
    VALUES((SELECT ID FROM Tour WHERE name="Anatolia Tour"),
    (SELECT ID FROM Hotel WHERE name="Sunflower Hotel"),
    "2019-01-01", "2019-01-15");
    
INSERT INTO TravelRoute(vehicle_type, company_name, tour_ID,
        from_city_ID, to_city_ID,
        dept_address, dept_time,
        arriv_address, arriv_time)
    VALUES("Bus", "Kamil", (SELECT ID FROM Tour WHERE name="Anatolia Tour"),
        (SELECT ID FROM City WHERE name="Ankara"), (SELECT ID FROM City WHERE name="Antalya"),
        "Ankara Bus Station", "2019-01-01 10:00:00",
        "Antalya Bus Station", "2019-01-01 18:00:00");

INSERT INTO Reservation(customer_ID, tour_ID, issue_date, payment_status, cancel_date)
    VALUES((SELECT ID FROM Account WHERE username="bahadir"),
    (SELECT ID FROM Tour WHERE name="Anatolia Tour"), "2018-12-30", "UNPAID", NULL);

INSERT INTO Dependent(national_ID, customer_ID, gender, date_of_birth, first_name, middle_name, last_name)
    VALUES(123451234, (SELECT ID FROM Account WHERE username="bahadir"), "Male", "1999-02-19", "Bahadir", "Little", "Child");
    
INSERT INTO IncludedDependents(reservation_ID, dependent_ID)
    VALUES((SELECT ID FROM Reservation WHERE customer_ID=(SELECT ID FROM Account WHERE username="bahadir")
        AND tour_ID=(SELECT ID FROM Tour WHERE name="Anatolia Tour")),
        (SELECT national_ID FROM Dependent WHERE first_name="Bahadir" AND last_name="Child"));

INSERT INTO Reservation(customer_ID, tour_ID, issue_date, payment_status, cancel_date)
    VALUES((SELECT ID FROM Account WHERE username="sami"),
    (SELECT ID FROM Tour WHERE name="Anatolia Tour"), "2018-12-30", "PAID", NULL);

INSERT INTO Tour(name, description, image_path, quota, price, creator_ID)
    VALUES("Europe Tour", "This is an europe tour...", "./images/europe.png", 3, 495.90,
    (SELECT ID FROM Account WHERE username="abdullah"));
    
INSERT INTO Hotel(city_ID, name, address, star_rating)
    VALUES((SELECT ID FROM City WHERE name="Sweden"), "Vikingen Hotel", "Another street, another road...", 5);

INSERT INTO Accommodation(tour_ID, place_ID, enter_date, exit_date)
    VALUES((SELECT ID FROM Tour WHERE name="Europe Tour"),
    (SELECT ID FROM Hotel WHERE name="Vikingen Hotel"),
    "2019-01-20", "2019-01-27");
    
INSERT INTO Accommodation(tour_ID, place_ID, enter_date, exit_date)
    VALUES((SELECT ID FROM Tour WHERE name="Europe Tour"),
    (SELECT ID FROM Hotel WHERE name="Sunflower Hotel"),
    "2019-01-28", "2019-01-30");
    
INSERT INTO TripEvent(tour_ID, city_ID, name, description, trip_date)
    VALUES((SELECT ID FROM Tour WHERE name="Europe Tour"),
    (SELECT ID FROM City WHERE name="Antalya"), "Fast Trip",
    "This is a nice trip event. You will tripping during this event.", "2019-01-21");
    
INSERT INTO TourDay(tour_ID, day_no, day_date, description)
    VALUES((SELECT ID FROM Tour WHERE name="Europe Tour"),
    1, "2019-01-21", "This is your first tour day. You will have fun");

INSERT INTO Tag(name) VALUES("Vegan");
INSERT INTO Tag(name) VALUES("Historic");
INSERT INTO Tag(name) VALUES("Short");
INSERT INTO Tag(name) VALUES("Long");

INSERT INTO TourTags(tour_ID, tag_ID)
    VALUES((SELECT ID FROM Tour WHERE name="Europe Tour"), (SELECT ID FROM Tag WHERE name="Vegan"));
    
INSERT INTO TourTags(tour_ID, tag_ID)
    VALUES((SELECT ID FROM Tour WHERE name="Europe Tour"), (SELECT ID FROM Tag WHERE name="Short"));
    
INSERT INTO TourTags(tour_ID, tag_ID)
    VALUES((SELECT ID FROM Tour WHERE name="Anatolia Tour"), (SELECT ID FROM Tag WHERE name="Historic"));
    
INSERT INTO TourTags(tour_ID, tag_ID)
    VALUES((SELECT ID FROM Tour WHERE name="Anatolia Tour"), (SELECT ID FROM Tag WHERE name="Short"));
    