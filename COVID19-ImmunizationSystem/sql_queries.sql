--Empty single quotes represent user input, file not runnable without the input

--Insert Operation
INSERT INTO patients (personal_health_num, full_name, birth_date, email, 
phone, registration_code, building_num, street, postal_code, city, province)
VALUES (''); --patient enters their own information in

--Delete Operation
DELETE FROM clinics 
WHERE clinic_ID = ''; --user can specify which clinic

--Update Operation
UPDATE patients 
SET staff_ID = '' --staff inputs their ID
WHERE personal_health_num = ''; --staff enters patient's personal health number that they have checked in

--Selection 
SELECT * 
FROM clinics
WHERE province = ''; --user can view all clinics from specified province 

--Projection
SELECT '' --supervisor can choose 1+ fields to view 
FROM patients;

--Join Query
SELECT DISTINCT m.vaccine_name 
FROM vaccines v, store s, manufacturers m
WHERE v.vaccine_ID = s.vaccine_ID AND m.manufacturer_ID = v.manufacturer_ID;

--Aggregation Query
SELECT COUNT(*)
FROM patients p, staff s 
WHERE s.clinic_ID = '' AND s.staff_ID = p.staff_ID;

--Nested Aggregation with Group-by
SELECT vaccine_name, COUNT(*) 
FROM 
(SELECT floor(months_between(CURRENT_DATE, birth_date) / 12), m.vaccine_name 
FROM patients p, patients_receive_vaccines pv, vaccines v, manufacturers m 
WHERE floor(months_between(CURRENT_DATE, birth_date) / 12) '' '' AND --supervisor can choose operator (<= or >=) and age
p.personal_health_num=pv.personal_health_num AND 
pv.vaccine_ID=v.vaccine_ID AND 
v.manufacturer_ID=m.manufacturer_ID) 
GROUP BY vaccine_name;

--Division Query
SELECT c.clinic_ID 
FROM clinics c
WHERE NOT EXISTS 
((SELECT DISTINCT m.vaccine_name from manufacturers m)
MINUS 
(SELECT m.vaccine_name
FROM store s, manufacturers m, vaccines v
WHERE v.manufacturer_ID = m.manufacturer_ID AND c.clinic_ID = s.clinic_ID AND v.vaccine_ID = s.vaccine_ID));