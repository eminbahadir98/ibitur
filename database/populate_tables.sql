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

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("bahadir", "bahadir@example.com", "123", "Emin", "Bahadir", "Tuluce");
INSERT INTO CustomerAccount(ID, national_ID, nationality, gender, date_of_birth)
    VALUES(LAST_INSERT_ID(), "12345678912", (SELECT ID FROM Country WHERE name="Turkey"), "Male", "1998-02-18");

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("abdullah", "abdullah@example.com", "123", "Abdullah", NULL, "Talayhan");
INSERT INTO StaffAccount(ID) VALUES(LAST_INSERT_ID());

INSERT INTO Tour(name, description, image_path, quota, price, creator_ID, cancelling_deadline)
    VALUES("Anatolia Tour", "This is an anatolia tour...", "anatolia.png", 5, 395.90,
    (SELECT ID FROM Account WHERE username="abdullah"), "2019-01-05");
    
INSERT INTO Hotel(city_ID, name, address, star_rating)
    VALUES((SELECT ID FROM City WHERE name="Antalya"), "Sunflower Hotel", "Some street, some road...", 4);

INSERT INTO Accommodation(tour_ID, place_ID, enter_date, exit_date)
    VALUES((SELECT ID FROM Tour WHERE name="Anatolia Tour"),
    (SELECT ID FROM Hotel WHERE name="Sunflower Hotel"),
    "2019-01-10", "2019-01-15");
    
INSERT INTO TravelRoute(vehicle_type, company_name, tour_ID,
        from_city_ID, to_city_ID,
        dept_address, dept_time,
        arriv_address, arriv_time)
    VALUES("Bus", "Kamil", (SELECT ID FROM Tour WHERE name="Anatolia Tour"),
        (SELECT ID FROM City WHERE name="Ankara"), (SELECT ID FROM City WHERE name="Antalya"),
        "Ankara Bus Station", "2019-01-10 10:00:00",
        "Antalya Bus Station", "2019-01-10 18:00:00");
