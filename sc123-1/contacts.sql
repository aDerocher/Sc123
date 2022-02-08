
-- Create a new user. I don't think I need this for this excersize though. Just a habit
-- CREATE USER contacts_user WITH PASSWORD 'password'

-- Create the database (normally with the owner of the user created)
CREATE DATABASE ContactsDB -- WITH OWNER contacts_user


CREATE TABLE Contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    occupation VARCHAR(100) NOT NULL,
    -- I would normally add both 'CreatedAt and UpdatedAt' columns as well
)

INSERT INTO Contacts (fullname, occupation)
VALUES
('Grace Hopper','Bug Discoverer')
('Charles Babbage','Analysis Engineer')
('Julia','Official Main Logo')";
