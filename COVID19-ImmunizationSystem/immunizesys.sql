/* UBC CPSC 304 - COVID-19 Immunization System
This script creates all the tables and data in the DB */

drop table clinics CASCADE CONSTRAINTS;
drop table patients CASCADE CONSTRAINTS;
drop table booths CASCADE CONSTRAINTS;
drop table reports;
drop table vaccines CASCADE CONSTRAINTS;
drop table manufacturers CASCADE CONSTRAINTS;
drop table staff CASCADE CONSTRAINTS;
drop table volunteers CASCADE CONSTRAINTS;
drop table supervisors CASCADE CONSTRAINTS;
drop table immunizers CASCADE CONSTRAINTS;
drop table patients_receive_vaccines;
drop table administered_by;
drop table assigned_to;
drop table store;

-- ENUM data type wasn't working, but if it did, for clinic_type -> ENUM('pop-up', 'pharmacy', 'hospital', 'community centre', 'drive-thru', 'office', 'other')
CREATE TABLE clinics (
  clinic_ID varchar(20) PRIMARY KEY,
  clinic_name varchar(255),
  clinic_type varchar(255),
  building_num varchar(10),
  street varchar(255),
  postal_code char(7),
  city char(30),
  province char(2));

INSERT INTO clinics VALUES ('clinic-0', 'Newport Public Health Unit', 'office', '205', 'Newport Dr', 'V3H 5C9', 'Coquitlam', 'BC');
INSERT INTO clinics VALUES ('clinic-1', 'Poirier', 'pop-up', '618', 'Poirier St', 'V3J 6A9', 'Vancouver', 'BC');
INSERT INTO clinics VALUES ('clinic-2', 'I.D.A - University Pharmacy', 'pharmacy', '5754', 'University Blvd', 'V4R 1B5', 'Vancouver', 'BC');
INSERT INTO clinics VALUES ('clinic-3', 'Glenpine Venue', 'community centre', '80', 'Post St', 'V7A 9B2', 'Richmond', 'BC');

INSERT INTO clinics VALUES ('clinic-4', 'Shoppers Drug Mart', 'pharmacy', '2440', 'Dundas St', 'M6P 1W9', 'Toronto', 'ON');
INSERT INTO clinics VALUES ('clinic-5', 'Deloitte', 'office', '8', 'Adelaide St W', 'M8L 1A2', 'Toronto', 'ON');
INSERT INTO clinics VALUES ('clinic-6', 'Rexall', 'pharmacy', '3555', 'Don Mills Rd', 'M2H 3N3', 'North York', 'ON');
INSERT INTO clinics VALUES ('clinic-7', 'Leslie Center', 'pop-up', '145', 'Leslie St', 'M2L 8Q4', 'North York', 'ON');
INSERT INTO clinics VALUES ('clinic-8', 'Bur Oak Drugs', 'pharmacy', '24', 'Bur Oak Ave', 'M3I 2N5', 'Markham', 'ON');
INSERT INTO clinics VALUES ('clinic-9', 'Missisauga Community Center', 'community centre', '8', 'Yonge St', 'M6P 1W9', 'Missisauga', 'ON');
INSERT INTO clinics VALUES ('clinic-10', 'Oakville General Hospital', 'hospital', '2440', 'Pinecone St', 'M5Y 2A7', 'Oakville', 'ON');

INSERT INTO clinics VALUES ('clinic-11', 'Bureau Acces Montreal', 'office', '1255', 'Decarie Blvd', 'G1R 1T2', 'Montreal', 'QC');
INSERT INTO clinics VALUES ('clinic-12', 'Lucien Community Center', 'community centre', '120', 'Lucien St', 'G9A 2B1', 'Quebec City', 'QC');

INSERT INTO clinics VALUES ('clinic-13', 'Unity Square Center','community centre', '75', 'Niagara St', 'E8A 7G9', 'Fredericton', 'NB');
INSERT INTO clinics VALUES ('clinic-14', 'Shoppers Drug Mart','pharmacy', '812', 'Scarburn St', 'E1B 2S8', 'Fredericton', 'NB');

INSERT INTO clinics VALUES ('clinic-15', 'Rexall', 'pharmacy', '35', 'Applewood Dr SE', 'T2C 4W9', 'Edmonton', 'AB');
INSERT INTO clinics VALUES ('clinic-16', 'Calgary Family Center', 'community centre', '2', 'Marlborough Way NE', 'T4B 5Y6', 'Calgary', 'AB');
INSERT INTO clinics VALUES ('clinic-17', 'Calgary Rent', 'pop-up', '9704', 'Radford Rd NE', 'T5R 9N8', 'Calgary', 'AB');

INSERT INTO clinics VALUES ('clinic-18', 'Whitehorse Community Center', 'community centre', '1109', 'Front St', 'Y1A 5G4', 'Whitehorse', 'YT');

CREATE TABLE staff (
  staff_ID varchar(20) PRIMARY KEY,
  full_name varchar(255),
  clinic_ID varchar(20),
  FOREIGN KEY (clinic_ID) REFERENCES clinics
    ON DELETE SET NULL);

INSERT INTO staff VALUES ('staff-0', 'Elizabeth Olsen', 'clinic-0');
INSERT INTO staff VALUES ('staff-5', 'Makoto Tachibana', 'clinic-0');
INSERT INTO staff VALUES ('staff-10', 'Midorima Shintaro', 'clinic-0');

INSERT INTO staff VALUES ('staff-1', 'Jun Nanase', 'clinic-1');
INSERT INTO staff VALUES ('staff-6', 'Hercule Poirot', 'clinic-1');
INSERT INTO staff VALUES ('staff-11', 'Jane Holmes', 'clinic-1');

INSERT INTO staff VALUES ('staff-2', 'Maryam Naderi', 'clinic-2');
INSERT INTO staff VALUES ('staff-7', 'Amelia Gold', 'clinic-2');
INSERT INTO staff VALUES ('staff-12', 'May Li', 'clinic-2');

INSERT INTO staff VALUES ('staff-3', 'Lily Sekhon', 'clinic-3');
INSERT INTO staff VALUES ('staff-8', 'Thomas Shelby', 'clinic-3');
INSERT INTO staff VALUES ('staff-13', 'Alexa Campos', 'clinic-3');

INSERT INTO staff VALUES ('staff-4', 'Robert Patt', 'clinic-4');
INSERT INTO staff VALUES ('staff-9', 'Helen Manoli', 'clinic-4');
INSERT INTO staff VALUES ('staff-14', 'Vernon Montgomery', 'clinic-4');

CREATE TABLE volunteers (
  staff_ID varchar(20) PRIMARY KEY,
  FOREIGN KEY (staff_ID) REFERENCES staff);

INSERT INTO volunteers VALUES ('staff-0');
INSERT INTO volunteers VALUES ('staff-1');
INSERT INTO volunteers VALUES ('staff-2');
INSERT INTO volunteers VALUES ('staff-3');
INSERT INTO volunteers VALUES ('staff-4');
    
CREATE TABLE supervisors (
  staff_ID varchar(20) PRIMARY KEY,
  FOREIGN KEY (staff_ID) REFERENCES staff);

INSERT INTO supervisors VALUES ('staff-5');
INSERT INTO supervisors VALUES ('staff-6');
INSERT INTO supervisors VALUES ('staff-7');
INSERT INTO supervisors VALUES ('staff-8');
INSERT INTO supervisors VALUES ('staff-9');

CREATE TABLE immunizers (
  staff_ID varchar(20) PRIMARY KEY,
  FOREIGN KEY (staff_ID) REFERENCES staff);

INSERT INTO immunizers VALUES ('staff-10');
INSERT INTO immunizers VALUES ('staff-11');
INSERT INTO immunizers VALUES ('staff-12');
INSERT INTO immunizers VALUES ('staff-13');
INSERT INTO immunizers VALUES ('staff-14');

CREATE TABLE patients (
  personal_health_num varchar(30) PRIMARY KEY,
  full_name varchar(255),
  birth_date DATE,
  email varchar(255),
  phone CHAR(12),
  building_num varchar(10),
  street varchar(255),
  postal_code char(7),
  city char(30),
  province char(2),
  staff_ID varchar(20),
  registration_code CHAR(10) UNIQUE,
  foreign key (staff_ID) REFERENCES volunteers);

INSERT INTO patients VALUES ('PH-0123456789', 'Kimia Rostin', DATE '2001-08-06', 'kimia@gmail.com', '778-123-4567', '1000', 'Blueberry Ln', 'V4T 8U2', 'Coquitlam', 'BC', 'staff-0', 'R-1a2s3d4f');
INSERT INTO patients VALUES ('PH-0000000000', 'Juila Han', DATE '1966-08-06', 'julia@gmail.com', '778-234-1235', '21', 'Lemon St', 'V2Y 3E9', 'Port Moody', 'BC', 'staff-0', 'R-2b6n39df');
INSERT INTO patients VALUES ('PH-9458034345', 'Janette Kim', DATE '2004-08-06', 'janette@gmail.com', '778-555-7777', '42', 'Midland Dr', 'V7A 9E0', 'Coquitlam', 'BC', 'staff-0', 'R-39bjs92s');
INSERT INTO patients VALUES ('PH-3841094444', 'Ashley Leung', DATE '1999-08-06', 'ashley@gmail.com', '778-345-6788', '5500', 'Iowa St', 'V2T 3B6', 'Richmond', 'BC', 'staff-0', 'R-1klb9e22');
INSERT INTO patients VALUES ('PH-3495954353', 'Cami Slopes', DATE '2002-08-06', 'cami@gmail.com', '778-456-8964', '123', 'Marvis Ln', 'V5E 2M8', 'Coquitlam', 'BC', 'staff-0', 'R-59jg93d3');

INSERT INTO patients VALUES ('PH-1234567890', 'Annette Chan', DATE '2003-07-06', 'annette@gmail.com', '778-666-2222', '145', 'Barnett St', 'V3J 2A3', 'Vancouver', 'BC', 'staff-1', 'R-1nb93id1');
INSERT INTO patients VALUES ('PH-5830859400', 'Katelyn Ngo', DATE '1959-09-14', 'katelyn@gmail.com', '604-999-1234', '3455', 'Main St', 'V5S 3N3', 'Vancouver', 'BC', 'staff-2', 'R-39tjf93e');
INSERT INTO patients VALUES ('PH-3280423940', 'Jack Highland', DATE '1973-09-14', 'jack@gmail.com', '604-000-9888', '2', 'York Mills Rd', 'V3E 4T8', 'Delta', 'BC', 'staff-2', 'R-29i3ied2');

INSERT INTO patients VALUES ('PH-3456789012', 'Anna Marvis', DATE '1945-09-14', 'anna@gmail.com', '604-356-9493', '40', 'Turnberry Ln', 'V6I 8T1', 'Vancouver', 'BC', 'staff-2', 'R-1d2s3d4f');
INSERT INTO patients VALUES ('PH-6859432565', 'Joshua Mills', DATE '2005-09-14', 'joashua@gmail.com', '604-584-3829', '216', 'Macowen Rd', 'V2B 1T9', 'Surrey', 'BC', 'staff-2', 'R-1jr9vn3f');
INSERT INTO patients VALUES ('PH-98765432456', 'Momo Lee', DATE '1978-09-14', 'momo@gmail.com', '604-549-9393', '99', 'Helen St', 'V9S 7F2', 'West Vancouver', 'BC', 'staff-2', 'R-19f94n59');

INSERT INTO patients VALUES ('PH-5432345643', 'Liam Lawliet', DATE '1979-10-31', 'lawliet@gmail.com', '604-234-3248', '90', 'Sugarcane St', 'V1A 5G1', 'Whitehorse', 'YT', 'staff-3', 'R-1e2s3d4f');
INSERT INTO patients VALUES ('PH-45643245456', 'Maria Osun', DATE '1948-10-31', 'mar@gmail.com', '604-111-1239', '234', 'Bridle Path', 'V5E 6B1', 'Whitehorse', 'YT', 'staff-3', 'R-4f93nf9r');

INSERT INTO patients VALUES ('PH-2345678901', 'Angela Zhou', DATE '2001-02-24', 'angela@gmail.com', '416-555-9999', '146', 'Rockridge Rd', 'M2L 4RB', 'Toronto', 'ON', 'staff-4', 'R-1c2s3d4f');
INSERT INTO patients VALUES ('PH-3453245456', 'Alexandra Anderson', DATE '2000-11-09', 'alexand@gmail.com', '647-333-2173', '9999', 'Bannatyne Rd', 'M3C 0H9', 'Toronto', 'ON', 'staff-4', 'R-9ejn39gf');
INSERT INTO patients VALUES ('PH-2342345456', 'Lily Williams', DATE '2006-06-10', 'lilyw@gmail.com', '467-809-2839', '111', 'Chipstead Rd', 'M1J 2P2', 'Scarborough', 'ON', 'staff-4', 'R-39cj39rf');
INSERT INTO patients VALUES ('PH-8765457654', 'Daniel Marks', DATE '1971-05-17', 'daniel@gmail.com', '416-274-5738', '234', 'Lesle St', 'M3I 1P2', 'Toronto', 'ON', 'staff-4', 'R-93m2o3ne');
INSERT INTO patients VALUES ('PH-3458765433', 'Jeno Smith', DATE '1955-04-17', 'jeno@gmail.com', '416-685-3485', '135', 'Eglinton Rd', 'M6B 0A1', 'Oakville', 'ON', 'staff-4', 'R-2iv84b5d');
INSERT INTO patients VALUES ('PH-5678989765', 'Mark Garcia', DATE '1997-06-10', 'mark@gmail.com', '467-356-2344', '20', 'Yonge St', 'M7B 2M0', 'Toronto', 'ON', 'staff-4', 'R-3f94nd0b');
INSERT INTO patients VALUES ('PH-67545654656', 'Chenle Miller', DATE '2000-12-21', 'chenle@gmail.com', '416-897-3455', '4', 'Cairnside Ave', 'M8A 1IL', 'Scarborough', 'ON', 'staff-4', 'R-0j493j4p');
INSERT INTO patients VALUES ('PH-4567890123', 'Jisung Jones', DATE '2002-02-02', 'jisung@gmail.com', '647-456-6567', '224', 'St Joseph Rd', 'M2F 1H0', 'Toronto', 'ON', 'staff-4', 'R-9v3j85f2');
INSERT INTO patients VALUES ('PH-78908765678', 'Renjun Davids', DATE '1988-07-30', 'renjun@gmail.com', '416-777-3333', '3', 'Sheppard Rd', 'M6A 8V3', 'Toronto', 'ON', 'staff-4', 'R-9en8fc35');
INSERT INTO patients VALUES ('PH-09890908790', 'Haechan Ark', DATE '1999-08-22', 'haechan@gmail.com', '647-552-1111', '1', 'Finch St', 'M2M 6G1', 'Toronto', 'ON', 'staff-4', 'R-20mr9fn3');
INSERT INTO patients VALUES ('PH-02002939392', 'Jaemin Papul', DATE '2004-10-25', 'jaemin@gmail.com', '467-331-5553', '234', 'Laneway Ave', 'M4R 5J1', 'North York', 'ON', 'staff-4', 'R-1j283jd4');

CREATE TABLE booths (
  booth_ID varchar(10),
  clinic_ID varchar(20),
  PRIMARY KEY (booth_ID, clinic_ID),
  FOREIGN KEY (clinic_ID) REFERENCES clinics
    ON DELETE CASCADE);

INSERT INTO booths VALUES ('booth-1A', 'clinic-0');
INSERT INTO booths VALUES ('booth-1B', 'clinic-0');

INSERT INTO booths VALUES ('booth-2A', 'clinic-1');

INSERT INTO booths VALUES ('booth-1A', 'clinic-2');
INSERT INTO booths VALUES ('booth-1B', 'clinic-2');
INSERT INTO booths VALUES ('booth-1C', 'clinic-2');
INSERT INTO booths VALUES ('booth-2A', 'clinic-2');

INSERT INTO booths VALUES ('booth-3A', 'clinic-3');
INSERT INTO booths VALUES ('booth-3B', 'clinic-3');

INSERT INTO booths VALUES ('booth-1A', 'clinic-4');
INSERT INTO booths VALUES ('booth-2A', 'clinic-4');
INSERT INTO booths VALUES ('booth-2B', 'clinic-4');
INSERT INTO booths VALUES ('booth-3B', 'clinic-4');

CREATE TABLE reports (
  report_ID varchar(20) PRIMARY KEY,
  submission_time timestamp,
  report_description varchar(255),
  staff_ID varchar(20) NOT NULL,
  FOREIGN key (staff_ID) REFERENCES supervisors);

INSERT INTO reports VALUES ('report-0', timestamp '2021-05-24 09:26:50.10', 'inventory updates', 'staff-5');
INSERT INTO reports VALUES ('report-1', timestamp '2021-05-24 17:00:00.47', 'reported incidents', 'staff-5');
INSERT INTO reports VALUES ('report-2', timestamp '2021-05-25 17:00:00.19', 'inventory updates', 'staff-5');
INSERT INTO reports VALUES ('report-3', timestamp '2021-05-26 12:35:03.29', 'workplace accidents', 'staff-9');
INSERT INTO reports VALUES ('report-4', timestamp '2021-04-01 11:04:34.00', 'security issue', 'staff-8');

CREATE TABLE manufacturers (
  manufacturer_ID varchar(30) PRIMARY KEY,
  vaccine_name varchar(255));

INSERT INTO manufacturers VALUES ('manufacturer-0', 'Moderna');
INSERT INTO manufacturers VALUES ('manufacturer-1', 'Moderna');
INSERT INTO manufacturers VALUES ('manufacturer-2', 'Pfizer-BioNTech');
INSERT INTO manufacturers VALUES ('manufacturer-3', 'AstraZeneca');
INSERT INTO manufacturers VALUES ('manufacturer-4', 'Janssen');
    
CREATE TABLE vaccines (
  vaccine_ID varchar(20) PRIMARY KEY,
  manufacturer_ID varchar(30) NOT NULL,
  FOREIGN key (manufacturer_id) REFERENCES manufacturers);

INSERT INTO vaccines VALUES ('vaccine-0', 'manufacturer-0');
INSERT INTO vaccines VALUES ('vaccine-1', 'manufacturer-0');
INSERT INTO vaccines VALUES ('vaccine-2', 'manufacturer-0');
INSERT INTO vaccines VALUES ('vaccine-3', 'manufacturer-1');
INSERT INTO vaccines VALUES ('vaccine-4', 'manufacturer-1');
INSERT INTO vaccines VALUES ('vaccine-5', 'manufacturer-2');
INSERT INTO vaccines VALUES ('vaccine-6', 'manufacturer-2');
INSERT INTO vaccines VALUES ('vaccine-7', 'manufacturer-3');
INSERT INTO vaccines VALUES ('vaccine-8', 'manufacturer-3');
INSERT INTO vaccines VALUES ('vaccine-9', 'manufacturer-3');
INSERT INTO vaccines VALUES ('vaccine-10', 'manufacturer-4');

CREATE TABLE assigned_to (
  booth_ID varchar(10),
  clinic_ID varchar(20),
  staff_ID varchar(20),
  primary key (booth_ID, clinic_ID, staff_ID),
  foreign key (staff_ID) REFERENCES immunizers,
  FOREIGN KEY (booth_ID, clinic_ID) references booths
    ON DELETE CASCADE);

INSERT INTO assigned_to VALUES ('booth-1A', 'clinic-0', 'staff-10');
INSERT INTO assigned_to VALUES ('booth-1B', 'clinic-0', 'staff-10');
INSERT INTO assigned_to VALUES ('booth-2A', 'clinic-1', 'staff-11');
INSERT INTO assigned_to VALUES ('booth-1C', 'clinic-2', 'staff-12');
INSERT INTO assigned_to VALUES ('booth-3A', 'clinic-3', 'staff-13');
INSERT INTO assigned_to VALUES ('booth-2B', 'clinic-4', 'staff-14');


CREATE TABLE store (
  clinic_ID varchar(20),
  vaccine_ID varchar(20),
  PRIMARY KEY (clinic_ID, vaccine_ID),
  foreign key (clinic_ID) references clinics
    ON DELETE CASCADE,
  foreign key (vaccine_ID) references vaccines);

INSERT INTO store VALUES ('clinic-0', 'vaccine-3');
INSERT INTO store VALUES ('clinic-0', 'vaccine-5');
INSERT INTO store VALUES ('clinic-0', 'vaccine-7');
INSERT INTO store VALUES ('clinic-0', 'vaccine-10');
INSERT INTO store VALUES ('clinic-1', 'vaccine-0');
INSERT INTO store VALUES ('clinic-1', 'vaccine-6');
INSERT INTO store VALUES ('clinic-4', 'vaccine-2');
INSERT INTO store VALUES ('clinic-4', 'vaccine-5');
INSERT INTO store VALUES ('clinic-4', 'vaccine-8');
INSERT INTO store VALUES ('clinic-4', 'vaccine-10');

CREATE TABLE administered_by (
	staff_ID varchar(20),
	personal_health_num varchar(30),
  vaccine_ID varchar(20),
  PRIMARY KEY (staff_ID, personal_health_num, vaccine_ID),
  foreign key (staff_ID) references immunizers,
  foreign key (personal_health_num) references patients,
  foreign key (vaccine_ID) references vaccines);

INSERT INTO administered_by VALUES ('staff-10', 'PH-1234567890', 'vaccine-5');
INSERT INTO administered_by VALUES ('staff-10', 'PH-3456789012', 'vaccine-0');
INSERT INTO administered_by VALUES ('staff-14', 'PH-4567890123', 'vaccine-2');

CREATE TABLE patients_receive_vaccines (
  personal_health_num varchar(30),
  vaccine_ID varchar(20),
  immunization_date DATE,
  PRIMARY KEY (personal_health_num, vaccine_ID),
  foreign key (personal_health_num) references patients,
  foreign key (vaccine_ID) references vaccines);

INSERT INTO patients_receive_vaccines VALUES ('PH-1234567890', 'vaccine-5', DATE '2021-05-24');
INSERT INTO patients_receive_vaccines VALUES ('PH-3456789012', 'vaccine-0', DATE '2021-05-25');
INSERT INTO patients_receive_vaccines VALUES ('PH-4567890123', 'vaccine-2', DATE '2021-05-24');
INSERT INTO patients_receive_vaccines VALUES ('PH-0000000000', 'vaccine-5', DATE '2021-06-01'); 
INSERT INTO patients_receive_vaccines VALUES ('PH-09890908790', 'vaccine-2', DATE '2021-06-01'); 
INSERT INTO patients_receive_vaccines VALUES ('PH-3841094444', 'vaccine-10', DATE '2021-06-04'); 
INSERT INTO patients_receive_vaccines VALUES ('PH-8765457654', 'vaccine-5', DATE '2021-06-04'); 
INSERT INTO patients_receive_vaccines VALUES ('PH-02002939392', 'vaccine-8', DATE '2021-06-05'); 
INSERT INTO patients_receive_vaccines VALUES ('PH-67545654656', 'vaccine-10', DATE '2021-06-05');