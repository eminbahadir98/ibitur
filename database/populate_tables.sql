USE ibitur_db;


INSERT INTO Country(name) VALUES("Turkey");
INSERT INTO Country(name) VALUES("England");
INSERT INTO Country(name) VALUES("Sweden");
INSERT INTO Country(name) VALUES("Germany");
INSERT INTO Country(name) VALUES("United States");
INSERT INTO Country(name) VALUES("Japan");

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("bahadir", "bahadir@example.com", "123", "Emin", "Bahadir", "Tuluce");
INSERT INTO CustomerAccount(ID, national_ID, nationality, gender, date_of_birth)
    VALUES(LAST_INSERT_ID(), "12345678912", (SELECT ID FROM Country WHERE name="Turkey"), "Male", "1998-02-18");

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("abdullah", "abdullah@example.com", "123", "Abdullah", NULL, "Talayhan");
INSERT INTO StaffAccount(ID) VALUES(LAST_INSERT_ID());
