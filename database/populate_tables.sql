USE ibitur_db;


INSERT INTO Country(name) VALUES("Turkey");
INSERT INTO Country(name) VALUES("England");

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("bahadir", "bahadir@example.com", "123", "Emin", "Bahadir", "Tuluce");
INSERT INTO CustomerAccount(national_ID, nationality, gender, date_of_birth)
    VALUES("12345678912", 1, "Male", "1998-02-18");

INSERT INTO Account(username, email, passwd, first_name, middle_name, last_name)
    VALUES("abdullah", "abdullah@example.com", "123", "Abdullah", NULL, "Talayhan");
INSERT INTO StaffAccount() VALUES();
