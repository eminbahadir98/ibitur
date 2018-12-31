USE ibitur_db;


CREATE TABLE Account (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50)  NOT NULL,
    passwd VARCHAR(50) NOT NULL,
    first_name VARCHAR(50),
    middle_name VARCHAR(50),
    last_name VARCHAR(50)
);

CREATE TABLE StaffAccount (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    FOREIGN KEY (ID) REFERENCES Account(ID)
);

CREATE TABLE Country (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(80) NOT NULL
);

CREATE TABLE CustomerAccount (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    national_ID VARCHAR(15),
    nationality INTEGER,
    gender VARCHAR(6),
    date_of_birth DATE,
    booking_points INTEGER DEFAULT 0,
    FOREIGN KEY (ID) REFERENCES Account(ID),
    FOREIGN KEY (nationality) REFERENCES Country(ID)
);

CREATE TABLE Dependent (
    national_ID INTEGER PRIMARY KEY,
    customer_ID INTEGER,
    gender VARCHAR(6) NOT NULL,
    date_of_birth DATE NOT NULL,
    first_name VARCHAR(50),
    middle_name VARCHAR(50),
    last_name VARCHAR(50),
    FOREIGN KEY (customer_ID) REFERENCES CustomerAccount(ID)
);

CREATE TABLE PromotionCard (
    promo_code VARCHAR(10) PRIMARY KEY,
    discount_percent NUMERIC(3,0) NOT NULL
);

CREATE TABLE CustomerPromotionCards (
    promo_code VARCHAR(10) PRIMARY KEY,
    customer_ID INTEGER,
    FOREIGN KEY (customer_ID) REFERENCES CustomerAccount(ID),
    FOREIGN KEY (promo_code) REFERENCES PromotionCard(promo_code)
);

CREATE TABLE CustomerTelephones (
    customer_ID INTEGER,
    telephone_no NUMERIC(15, 0),
    PRIMARY KEY (customer_ID , telephone_no),
    FOREIGN KEY  (customer_ID) REFERENCES CustomerAccount(ID)
);

CREATE TABLE Tour (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    description VARCHAR(1000),
    image_path VARCHAR(500),
    quota INTEGER DEFAULT 0,
    price NUMERIC (7,2) DEFAULT 0,
    creator_ID INTEGER,
    cancelling_deadline DATETIME NOT NULL,
    FOREIGN KEY (creator_ID) REFERENCES StaffAccount(ID)
);

CREATE TABLE Reservation (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    customer_ID INTEGER,
    tour_ID INTEGER,
    issue_date DATETIME NOT NULL,
    payment_status VARCHAR(6) NOT NULL,
    cancel_date DATETIME,
    FOREIGN KEY (customer_ID) REFERENCES CustomerAccount(ID),
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID)
);

CREATE TABLE WaitingList (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    customer_ID INTEGER,
    tour_ID INTEGER,
    issue_date DATETIME NOT NULL,
    FOREIGN KEY (customer_ID) REFERENCES CustomerAccount(ID),
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID)
);

CREATE TABLE IncludedDependents(
    reservation_ID INTEGER,
    dependent_ID INTEGER,
    PRIMARY KEY (reservation_ID, dependent_ID),
    FOREIGN KEY (reservation_ID) REFERENCES Reservation(ID),
    FOREIGN KEY (dependent_ID) REFERENCES Dependent(national_ID)
);

CREATE TABLE TourCancel (
    tour_ID INTEGER,
    cancel_date DATETIME NOT NULL,
    cancel_reason VARCHAR(300),
    PRIMARY KEY (tour_ID),
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID)
);

CREATE TABLE Tag (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR( 15) NOT NULL,
    tag_type VARCHAR(15) NOT NULL
);

CREATE TABLE TourTags (
    tour_ID INTEGER,
    tag_ID INTEGER,
    PRIMARY KEY (tour_ID, tag_ID),
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID),
    FOREIGN KEY (tag_ID) REFERENCES Tag(ID)
);

CREATE TABLE TourDay (
    tour_ID INTEGER,
    day_no INTEGER,
    day_date DATE,
    description VARCHAR(1000),
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID),
    PRIMARY KEY (tour_ID, day_no)
);

CREATE TABLE City (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(10) NOT NULL,
    country_ID INTEGER NOT NULL,
    FOREIGN KEY (country_ID) REFERENCES Country(ID)
);

CREATE TABLE Hotel (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    city_ID INTEGER,
    name VARCHAR(45) NOT NULL,
    address VARCHAR(1000),
    star_rating NUMERIC(1,0) DEFAULT 0,
    FOREIGN KEY (city_ID) REFERENCES City(ID)
);

CREATE TABLE Accommodation (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    tour_ID INTEGER,
    place_ID INTEGER,
    enter_date DATETIME,
    exit_date DATETIME,
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID),
    FOREIGN KEY (place_ID) REFERENCES Hotel(ID)
);

CREATE TABLE TripEvent (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    tour_ID INTEGER,
    city_ID INTEGER,
    name VARCHAR(20),
    description VARCHAR(300),
    trip_date DATETIME,
    FOREIGN KEY (city_ID) REFERENCES City(ID),
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID)
);

CREATE TABLE TravelRoute (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    vehicle_type VARCHAR(20),
    company_name VARCHAR(20),
    tour_ID INTEGER,
    from_city_ID INTEGER,
    to_city_ID INTEGER,
    dept_address VARCHAR(60),
    dept_time DATETIME,
    arriv_address VARCHAR(60),
    arriv_time DATETIME,
    FOREIGN KEY (tour_ID) REFERENCES Tour(ID),
    FOREIGN KEY (from_city_ID) REFERENCES City(ID),
    FOREIGN KEY (to_city_ID) REFERENCES City(ID)
);

CREATE TABLE CustomerTravels (
    travel_route_ID INTEGER,
    customer_ID INTEGER,
    pnr_no VARCHAR(10),
    FOREIGN KEY (travel_route_ID) REFERENCES TravelRoute(ID),
    FOREIGN KEY (customer_ID) REFERENCES CustomerAccount(ID),
    PRIMARY KEY (travel_route_ID, customer_ID)
);

CREATE TABLE CustomerAccommodates (
    accommodation_ID INTEGER,
    customer_ID INTEGER,
    room_no VARCHAR(6),
    FOREIGN KEY (accommodation_ID) REFERENCES Accommodation(ID),
    FOREIGN KEY (customer_ID) REFERENCES CustomerAccount(ID),
    PRIMARY KEY (accommodation_ID, customer_ID)
);

CREATE TABLE DependentAccommodates (
    national_ID INTEGER,
    accommodation_ID INTEGER,
    room_no VARCHAR(6),
    FOREIGN KEY (accommodation_ID) REFERENCES Accommodation(ID),
    FOREIGN KEY (national_ID) REFERENCES Dependent(national_ID),
    PRIMARY KEY (accommodation_ID, national_ID)
);
