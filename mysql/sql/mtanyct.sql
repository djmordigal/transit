--
-- mtanyct GTFS Database Creation Script
-- author: djm
--
-- This script will drop and recreate the GTFS static database for MTA New York
-- City Transit (subway and bus routes for all 5 boroughs).
--
DROP DATABASE IF EXISTS `mtanyct`;
CREATE DATABASE `mtanyct`;
USE `mtanyct`;

--
-- Table agency
--
CREATE TABLE `agency` (
    `agency_id` CHAR(8) NOT NULL,
    `agency_name` CHAR(32) NOT NULL,
    `agency_url` CHAR(32) NULL,
    `agency_timezone` CHAR(16) NOT NULL,
    `agency_lang` CHAR(2) NOT NULL,
    `agency_phone` CHAR(16) NOT NULL,
    PRIMARY KEY(`agency_id`)
);

--
-- Table routes
--
CREATE TABLE `routes` (
    `route_id` CHAR(8) NOT NULL,
    `agency_id` CHAR(8) NOT NULL,
    `route_short_name` CHAR(8) NOT NULL,
    `route_long_name` CHAR(128) NOT NULL,
    `route_desc` TEXT NULL,
    `route_type` TINYINT NOT NULL,
    `route_url` VARCHAR(255) NULL,
    `route_color` CHAR(6) NOT NULL,
    `route_text_color` CHAR(6) NOT NULL,
    PRIMARY KEY(`route_id`),
    FOREIGN KEY(`agency_id`) REFERENCES `agency`(`agency_id`)
);

--
-- Table calendar
--
CREATE TABLE `calendar` (
    `service_id` CHAR(32) NOT NULL,
    `monday` TINYINT NOT NULL,
    `tuesday` TINYINT NOT NULL,
    `wednesday` TINYINT NOT NULL,
    `thursday` TINYINT NOT NULL,
    `friday` TINYINT NOT NULL,
    `saturday` TINYINT NOT NULL,
    `sunday` TINYINT NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    PRIMARY KEY(`service_id`)
);

--
-- Table calendar_dates
--
CREATE TABLE `calendar_dates` (
    `service_id` CHAR(32) NOT NULL,
    `date` DATE NOT NULL,
    `exception_type` TINYINT NOT NULL,
    FOREIGN KEY(`service_id`) REFERENCES `calendar`(`service_id`)
);

--
-- Table shapes
--
CREATE TABLE `shapes` (
    `shape_id` CHAR(16) NOT NULL,
    `shape_pt_lat` DECIMAL(10,6) NOT NULL,
    `shape_pt_lon` DECIMAL(10,6) NOT NULL,
    `shape_pt_sequence` INT NOT NULL,
    `shape_dist_traveled` CHAR(0) NULL,
    INDEX(`shape_id`)
);

--
-- Table trips
--
CREATE TABLE `trips` (
    `route_id` CHAR(8) NOT NULL,
    `service_id` CHAR(32) NOT NULL,
    `trip_id` CHAR(64) NOT NULL,
    `trip_headsign` CHAR(64) NOT NULL,
    `direction_id` TINYINT NOT NULL,
    `block_id` CHAR(8) NULL,
    `shape_id` CHAR(16) NULL,
    PRIMARY KEY(`trip_id`),
    FOREIGN KEY(`route_id`) REFERENCES `routes`(`route_id`),
    FOREIGN KEY(`service_id`) REFERENCES `calendar`(`service_id`),
    FOREIGN KEY(`shape_id`) REFERENCES `shapes`(`shape_id`)
);

--
-- Table stops
--
CREATE TABLE `stops` (
    `stop_id` CHAR(8) NOT NULL,
    `stop_code` CHAR(0) NULL,
    `stop_name` CHAR(64) NOT NULL,
    `stop_desc` CHAR(0) NULL,
    `stop_lat` DECIMAL(10,6) NOT NULL,
    `stop_lon` DECIMAL(10,6) NOT NULL,
    `zone_id` CHAR(0) NULL,
    `stop_url` CHAR(0) NULL,
    `location_type` TINYINT NOT NULL,
    `parent_station` CHAR(8) NULL,
    PRIMARY KEY(`stop_id`),
    FOREIGN KEY(`parent_station`) REFERENCES `stops`(`stop_id`)
);

--
-- Table stop_times
--
CREATE TABLE `stop_times` (
    `trip_id` CHAR(64) NOT NULL,
    `arrival_time` TIME NOT NULL,
    `departure_time` TIME NOT NULL,
    `stop_id` CHAR(8) NOT NULL,
    `stop_sequence` INT NOT NULL,
    `stop_headsign` CHAR(0) NULL,
    `pickup_type` TINYINT NOT NULL,
    `drop_off_type` TINYINT NOT NULL,
    `shape_dist_traveled` CHAR(0) NULL,
    `timepoint` TINYINT NULL,
    FOREIGN KEY(`trip_id`) REFERENCES `trips`(`trip_id`),
    FOREIGN KEY(`stop_id`) REFERENCES `stops`(`stop_id`)
);

--
-- Table transfers
--
CREATE TABLE `transfers` (
    `from_stop_id` CHAR(8) NOT NULL,
    `to_stop_id` CHAR(8) NOT NULL,
    `transfer_type` TINYINT NOT NULL,
    `min_transfer_time` INT NOT NULL,
    FOREIGN KEY(`from_stop_id`) REFERENCES `stops`(`stop_id`),
    FOREIGN KEY(`to_stop_id`) REFERENCES `stops`(`stop_id`)
);

--
-- Supplemental stations table
--
CREATE TABLE `stations` (
    `station_id` INT NOT NULL AUTO_INCREMENT,
    `gtfs_stop_id` CHAR(8) NOT NULL,
    `borough` CHAR(2) NOT NULL,
    `daytime_routes` CHAR(16) NOT NULL,
    `north_label` CHAR(64) NULL,
    `south_label` CHAR(64) NULL,
    `ada` TINYINT NOT NULL,
    `ada_direction_notes` CHAR(64) NULL,
    `ada_nb` TINYINT NULL,
    `ada_sb` TINYINT NULL,
    PRIMARY KEY(`station_id`),
    FOREIGN KEY(`gtfs_stop_id`) REFERENCES `stops`(`stop_id`)
);

-- -----------------------------------------------------------------------------
--
-- Load Data
--
-- Files should be read in the same order in which the tables were created, so
-- as to preserve the foreign key integrity:
--      agency (only necessary for subway, since identical otherwise)
--      routes (only necessary for subway and 1 bus set, since identical otherwise)
--      calendar
--      calendar_dates
--      shapes
--      trips
--      stops
--      stop_times
--      transfers
--
-- -----------------------------------------------------------------------------
--
-- Subway Feeds
--
-- -----------------------------------------------------------------------------
LOAD DATA LOCAL INFILE '../feeds/google_transit/agency.txt' IGNORE
    INTO TABLE `agency`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`agency_id`,
     `agency_name`,
     @agency_url,
     `agency_timezone`,
     `agency_lang`,
     `agency_phone`)
    SET `agency_url` = TRIM(@agency_url);

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit/routes.txt' IGNORE
    INTO TABLE `routes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`agency_id`,
     `route_id`,
     `route_short_name`,
     `route_long_name`,
     `route_type`,
     `route_desc`,
     `route_url`,
     `route_color`,
     `route_text_color`);

SHOW WARNINGS LIMIT 10;

-- clean up route colors
UPDATE `routes` SET `route_color` = '0079C7' WHERE `route_id` = 'SI';
UPDATE `routes` SET `route_color` = '6D6E71' WHERE `route_id` IN ('FS', 'H');
UPDATE `routes` SET `route_text_color` = '000000'
    WHERE `route_id` IN ('N', 'Q', 'R', 'W');
UPDATE `routes` SET `route_text_color` = 'FFFFFF'
    WHERE `route_id` NOT IN ('N', 'Q', 'R', 'W');

LOAD DATA LOCAL INFILE '../feeds/google_transit/calendar.txt' IGNORE
    INTO TABLE `calendar`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     `monday`,
     `tuesday`,
     `wednesday`,
     `thursday`,
     `friday`,
     `saturday`,
     `sunday`,
     @start_date,
     @end_date)
    SET `start_date` = STR_TO_DATE(@start_date, '%Y%m%d'),
        `end_date` = STR_TO_DATE(@end_date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit/calendar_dates.txt' IGNORE
    INTO TABLE `calendar_dates`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     @date,
     `exception_type`)
    SET `date` = STR_TO_DATE(@date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit/shapes.txt' IGNORE
    INTO TABLE `shapes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`shape_id`,
     `shape_pt_sequence`,
     `shape_pt_lat`,
     `shape_pt_lon`);

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit/trips.txt' IGNORE
    INTO TABLE `trips`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`route_id`,
     `trip_id`,
     `service_id`,
     `trip_headsign`,
     `direction_id`,
     @shape_id)
    SET `shape_id` = NULLIF(@shape_id, '');

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit/stops.txt' IGNORE
    INTO TABLE `stops`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`stop_id`,
     `stop_name`,
     `stop_lat`,
     `stop_lon`,
     @location_type,
     @parent_station)
    SET `location_type` = IF(@location_type = '', 0, @location_type),
    `parent_station` = NULLIF(@parent_station, '');

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit/stop_times.txt' IGNORE
    INTO TABLE `stop_times`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`trip_id`,
     `stop_id`,
     `arrival_time`,
     `departure_time`,
     `stop_sequence`);

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit/transfers.txt' IGNORE
    INTO TABLE `transfers`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`from_stop_id`,
     `to_stop_id`,
     `transfer_type`,
     `min_transfer_time`);

SHOW WARNINGS LIMIT 10;

-- -----------------------------------------------------------------------------
--
-- Bronx Bus Feeds
--
-- -----------------------------------------------------------------------------
-- all 5 bus routes files are identical, so only one is needed
LOAD DATA LOCAL INFILE '../feeds/google_transit_bronx/routes.txt' IGNORE
    INTO TABLE `routes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`route_id`,
     `agency_id`,
     `route_short_name`,
     `route_long_name`,
     `route_desc`,
     @route_type,
     `route_color`,
     `route_text_color`)
    SET `route_type` = 3;

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit_bronx/calendar.txt' IGNORE
    INTO TABLE `calendar`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     `monday`,
     `tuesday`,
     `wednesday`,
     `thursday`,
     `friday`,
     `saturday`,
     `sunday`,
     @start_date,
     @end_date)
    SET `start_date` = STR_TO_DATE(@start_date, '%Y%m%d'),
        `end_date` = STR_TO_DATE(@end_date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

-- feed has stale calendar_dates records
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_bronx/calendar_dates.txt' IGNORE
    INTO TABLE `calendar_dates`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     @date,
     `exception_type`)
    SET `date` = STR_TO_DATE(@date, '%Y%m%d');
SHOW WARNINGS LIMIT 10;
DELETE FROM `calendar_dates` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_bronx/shapes.txt' IGNORE
    INTO TABLE `shapes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`shape_id`,
     `shape_pt_lat`,
     `shape_pt_lon`,
     `shape_pt_sequence`);

SHOW WARNINGS LIMIT 10;

-- feed has stale service_id references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_bronx/trips.txt' IGNORE
    INTO TABLE `trips`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`route_id`,
     `service_id`,
     `trip_id`,
     `trip_headsign`,
     `direction_id`,
     `block_id`,
     `shape_id`);
SHOW WARNINGS LIMIT 10;
DELETE FROM `trips` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_bronx/stops.txt' IGNORE
    INTO TABLE `stops`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`stop_id`,
     `stop_name`,
     `stop_desc`,
     `stop_lat`,
     `stop_lon`,
     `zone_id`,
     `stop_url`,
     `location_type`,
     @parent_station)
    SET `parent_station` = NULLIF(@parent_station, '');

SHOW WARNINGS LIMIT 10;

-- feed has stale trip references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_bronx/stop_times.txt' IGNORE
    INTO TABLE `stop_times`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`trip_id`,
     `arrival_time`,
     `departure_time`,
     `stop_id`,
     `stop_sequence`,
     `pickup_type`,
     `drop_off_type`,
     `timepoint`);
SHOW WARNINGS LIMIT 10;
DELETE FROM `stop_times` WHERE `trip_id` NOT IN
    (SELECT `trip_id` FROM `trips`);
SET foreign_key_checks = 1;

-- -----------------------------------------------------------------------------
--
-- Brooklyn Bus Feeds
--
-- -----------------------------------------------------------------------------
LOAD DATA LOCAL INFILE '../feeds/google_transit_brooklyn/calendar.txt' IGNORE
    INTO TABLE `calendar`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     `monday`,
     `tuesday`,
     `wednesday`,
     `thursday`,
     `friday`,
     `saturday`,
     `sunday`,
     @start_date,
     @end_date)
    SET `start_date` = STR_TO_DATE(@start_date, '%Y%m%d'),
        `end_date` = STR_TO_DATE(@end_date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit_brooklyn/calendar_dates.txt' IGNORE
    INTO TABLE `calendar_dates`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     @date,
     `exception_type`)
    SET `date` = STR_TO_DATE(@date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit_brooklyn/shapes.txt' IGNORE
    INTO TABLE `shapes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`shape_id`,
     `shape_pt_lat`,
     `shape_pt_lon`,
     `shape_pt_sequence`);

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit_brooklyn/trips.txt' IGNORE
    INTO TABLE `trips`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`route_id`,
     `service_id`,
     `trip_id`,
     `trip_headsign`,
     `direction_id`,
     `block_id`,
     `shape_id`);

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit_brooklyn/stops.txt' IGNORE
    INTO TABLE `stops`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`stop_id`,
     `stop_name`,
     `stop_desc`,
     `stop_lat`,
     `stop_lon`,
     `zone_id`,
     `stop_url`,
     `location_type`,
     @parent_station)
    SET `parent_station` = NULLIF(@parent_station, '');

SHOW WARNINGS LIMIT 10;

LOAD DATA LOCAL INFILE '../feeds/google_transit_brooklyn/stop_times.txt' IGNORE
    INTO TABLE `stop_times`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`trip_id`,
     `arrival_time`,
     `departure_time`,
     `stop_id`,
     `stop_sequence`,
     `pickup_type`,
     `drop_off_type`,
     `timepoint`);

SHOW WARNINGS LIMIT 10;

-- -----------------------------------------------------------------------------
--
-- Manhattan Bus Feeds
--
-- -----------------------------------------------------------------------------
LOAD DATA LOCAL INFILE '../feeds/google_transit_manhattan/calendar.txt' IGNORE
    INTO TABLE `calendar`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     `monday`,
     `tuesday`,
     `wednesday`,
     `thursday`,
     `friday`,
     `saturday`,
     `sunday`,
     @start_date,
     @end_date)
    SET `start_date` = STR_TO_DATE(@start_date, '%Y%m%d'),
        `end_date` = STR_TO_DATE(@end_date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

-- feed contains stale calendar references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_manhattan/calendar_dates.txt' IGNORE
    INTO TABLE `calendar_dates`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     @date,
     `exception_type`)
    SET `date` = STR_TO_DATE(@date, '%Y%m%d');
SHOW WARNINGS LIMIT 10;
DELETE FROM `calendar_dates` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_manhattan/shapes.txt' IGNORE
    INTO TABLE `shapes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`shape_id`,
     `shape_pt_lat`,
     `shape_pt_lon`,
     `shape_pt_sequence`);

SHOW WARNINGS LIMIT 10;

-- feed has stale calendar references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_manhattan/trips.txt' IGNORE
    INTO TABLE `trips`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`route_id`,
     `service_id`,
     `trip_id`,
     `trip_headsign`,
     `direction_id`,
     `block_id`,
     `shape_id`);
SHOW WARNINGS LIMIT 10;
DELETE FROM `trips` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_manhattan/stops.txt' IGNORE
    INTO TABLE `stops`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`stop_id`,
     `stop_name`,
     `stop_desc`,
     `stop_lat`,
     `stop_lon`,
     `zone_id`,
     `stop_url`,
     `location_type`,
     @parent_station)
    SET `parent_station` = NULLIF(@parent_station, '');

SHOW WARNINGS LIMIT 10;

-- feed has stale trip references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_manhattan/stop_times.txt' IGNORE
    INTO TABLE `stop_times`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`trip_id`,
     `arrival_time`,
     `departure_time`,
     `stop_id`,
     `stop_sequence`,
     `pickup_type`,
     `drop_off_type`,
     `timepoint`);
SHOW WARNINGS LIMIT 10;
DELETE FROM `stop_times` WHERE `trip_id` NOT IN
    (SELECT `trip_id` FROM `trips`);
SET foreign_key_checks = 1;

-- -----------------------------------------------------------------------------
--
-- Queens Bus Feeds
--
-- -----------------------------------------------------------------------------
LOAD DATA LOCAL INFILE '../feeds/google_transit_queens/calendar.txt' IGNORE
    INTO TABLE `calendar`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     `monday`,
     `tuesday`,
     `wednesday`,
     `thursday`,
     `friday`,
     `saturday`,
     `sunday`,
     @start_date,
     @end_date)
    SET `start_date` = STR_TO_DATE(@start_date, '%Y%m%d'),
        `end_date` = STR_TO_DATE(@end_date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

-- feed has stale calendar references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_queens/calendar_dates.txt' IGNORE
    INTO TABLE `calendar_dates`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     @date,
     `exception_type`)
    SET `date` = STR_TO_DATE(@date, '%Y%m%d');
SHOW WARNINGS LIMIT 10;
DELETE FROM `calendar_dates` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_queens/shapes.txt' IGNORE
    INTO TABLE `shapes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`shape_id`,
     `shape_pt_lat`,
     `shape_pt_lon`,
     `shape_pt_sequence`);

SHOW WARNINGS LIMIT 10;

-- feed has stale calendar references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_queens/trips.txt' IGNORE
    INTO TABLE `trips`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`route_id`,
     `service_id`,
     `trip_id`,
     `trip_headsign`,
     `direction_id`,
     `block_id`,
     `shape_id`);
SHOW WARNINGS LIMIT 10;
DELETE FROM `trips` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_queens/stops.txt' IGNORE
    INTO TABLE `stops`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`stop_id`,
     `stop_name`,
     `stop_desc`,
     `stop_lat`,
     `stop_lon`,
     `zone_id`,
     `stop_url`,
     `location_type`,
     @parent_station)
    SET `parent_station` = NULLIF(@parent_station, '');

SHOW WARNINGS LIMIT 10;

-- feed has stale trips references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_queens/stop_times.txt' IGNORE
    INTO TABLE `stop_times`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`trip_id`,
     `arrival_time`,
     `departure_time`,
     `stop_id`,
     `stop_sequence`,
     `pickup_type`,
     `drop_off_type`,
     `timepoint`);
SHOW WARNINGS LIMIT 10;

DELETE FROM `stop_times` WHERE `trip_id` NOT IN
    (SELECT `trip_id` FROM `trips`);
SET foreign_key_checks = 1;

-- -----------------------------------------------------------------------------
--
-- Staten Island Bus Feeds
--
-- -----------------------------------------------------------------------------
LOAD DATA LOCAL INFILE '../feeds/google_transit_staten_island/calendar.txt' IGNORE
    INTO TABLE `calendar`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     `monday`,
     `tuesday`,
     `wednesday`,
     `thursday`,
     `friday`,
     `saturday`,
     `sunday`,
     @start_date,
     @end_date)
    SET `start_date` = STR_TO_DATE(@start_date, '%Y%m%d'),
        `end_date` = STR_TO_DATE(@end_date, '%Y%m%d');

SHOW WARNINGS LIMIT 10;

-- feed has stale calendar references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_staten_island/calendar_dates.txt' IGNORE
    INTO TABLE `calendar_dates`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`service_id`,
     @date,
     `exception_type`)
    SET `date` = STR_TO_DATE(@date, '%Y%m%d');
SHOW WARNINGS LIMIT 10;
DELETE FROM `calendar_dates` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_staten_island/shapes.txt' IGNORE
    INTO TABLE `shapes`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`shape_id`,
     `shape_pt_lat`,
     `shape_pt_lon`,
     `shape_pt_sequence`);

SHOW WARNINGS LIMIT 10;

-- feed has stale calendar references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_staten_island/trips.txt' IGNORE
    INTO TABLE `trips`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`route_id`,
     `service_id`,
     `trip_id`,
     `trip_headsign`,
     `direction_id`,
     `block_id`,
     `shape_id`);
SHOW WARNINGS LIMIT 10;
DELETE FROM `trips` WHERE `service_id` NOT IN
    (SELECT `service_id` FROM `calendar`);
SET foreign_key_checks = 1;

LOAD DATA LOCAL INFILE '../feeds/google_transit_staten_island/stops.txt' IGNORE
    INTO TABLE `stops`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`stop_id`,
     `stop_name`,
     `stop_desc`,
     `stop_lat`,
     `stop_lon`,
     `zone_id`,
     `stop_url`,
     `location_type`,
     @parent_station)
    SET `parent_station` = NULLIF(@parent_station, '');

SHOW WARNINGS LIMIT 10;

-- feed has stale trips references
SET foreign_key_checks = 0;
LOAD DATA LOCAL INFILE '../feeds/google_transit_staten_island/stop_times.txt' IGNORE
    INTO TABLE `stop_times`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (`trip_id`,
     `arrival_time`,
     `departure_time`,
     `stop_id`,
     `stop_sequence`,
     `pickup_type`,
     `drop_off_type`,
     `timepoint`);
SHOW WARNINGS LIMIT 10;
DELETE FROM `stop_times` WHERE `trip_id` NOT IN
    (SELECT `trip_id` FROM `trips`);
SET foreign_key_checks = 1;

--
-- Supplemental Station Information
--
LOAD DATA LOCAL INFILE '../feeds/Stations.csv' IGNORE
    INTO TABLE `stations`
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (@dummy,
     @dummy,
     `gtfs_stop_id`,
     @dummy,
     @dummy,
     @dummy,
     `borough`,
     `daytime_routes`,
     @dummy,
     @dummy,
     @dummy,
     @north_label,
     @south_label,
     `ada`,
     @ada_direction_notes,
     @ada_nb,
     @ada_sb,
     @dummy,
     @dummy)
    SET `ada_direction_notes` = NULLIF(@ada_direction_notes, ''),
        `ada_nb` = NULLIF(@ada_nb, ''),
        `ada_sb` = NULLIF(@ada_sb, ''),
        `north_label` = NULLIF(@north_label, ''),
        `south_label` = NULLIF(@south_label, '');

--
-- Supplemental route information (derived from the feed itself, but greatly
-- improves search times)
--
CREATE TABLE stop_routes (
    `stop_id` CHAR(8) NOT NULL,
    `route_string` CHAR(128) NULL,
    PRIMARY KEY(`stop_id`),
    FOREIGN KEY(`stop_id`) REFERENCES `stops`(`stop_id`)
);

INSERT INTO `stop_routes`(`stop_id`, `route_string`)
SELECT stops.stop_id,
    GROUP_CONCAT(DISTINCT trips.route_id ORDER BY trips.route_id
        SEPARATOR ' ')
FROM stops
JOIN stop_times USING (stop_id)
JOIN trips USING (trip_id)
GROUP BY stop_times.stop_id;
SHOW WARNINGS LIMIT 10;
