--ROWID is auto included by sqlite, and will be our IDs
-- Your gonna need "PRAGMA foreign_keys = ON;"

CREATE TABLE `person` (
	`first_name` VARCHAR(255),
	`last_name` VARCHAR(255),
	`created_on` INT(255),
	`phone` VARCHAR(255),
	`email` VARCHAR(255)
);
    
CREATE TABLE `parent` (
	`baby_first_name` VARCHAR(255),
	`baby_last_name` VARCHAR(255),
	`baby_birthday` INT(255),
	`created_on` INT(255),
	`person_row_id` INT(255),
	`gcalId` VARCHAR(255),
	`gcalToken` TEXT
);

CREATE TABLE `helper` (
	`first_name` VARCHAR(255),
	`last_name` VARCHAR(255),
	`created_on` INT(255),
	`sms_ok` INT(255),
	`person_row_id` INT(255),
	`parent_row_id` INT(255)
);

CREATE TABLE `sent_text` (
	`content` VARCHAR(255),
	`created_on` INT(255),
	`error_code` INT(255),
	`person_row_id` INT(255),
    `response_row_id` INT(255)
);

CREATE TABLE `received_text` (
	`content` VARCHAR(255),
	`created_on` INT(255),
	`error_code` INT(255),
	`person_row_id` INT(255),
	`option_row_id` INT(255),
    `sent_row_id` INT(255) --The next text we send, in responce to this
);

CREATE TABLE `option` (
	`content` VARCHAR(255),
	`code` VARCHAR(255),
	`type_row_id` INT(255),
	`created_on` INT(255),
    `sent_row_id` INT(255) 
);