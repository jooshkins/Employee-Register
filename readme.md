## Example Restaurant Employee Registration App
A simple PHP example web app for managing employees in a mySQL database. 

## Database Schema
MySQL Database needed.

Database uses two tables one for employees and one for locations.

Use the following SQL commands to setup a simple database
```
create database restaurant;

use restaurant;

CREATE TABLE employee (
    id int AUTO_INCREMENT,
    LastName varchar(255) NOT NULL,
    FirstName varchar(255) NOT NULL,
    Location varchar(255) NOT NULL,
    Status BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (id)
);

CREATE TABLE location (
    id int AUTO_INCREMENT,
    City varchar(255) NOT NULL,
    State varchar(2) NOT NULL,
    PRIMARY KEY (id)
);
```
## Info Needed
Fill out `mysql_config.php` with database connection info and store in a safe place.  