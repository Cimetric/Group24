CREATE TABLE colors (id INT, name VARCHAR(255), hex_value VARCHAR(255));

INSERT INTO colors (id, name, GPA) VALUES 
(800000001, 'Red', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000002, 'Orange', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000003, 'Yellow', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000004, 'Green', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000005, 'Blue', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000006, 'Purple', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000007, 'Grey', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000008, 'Brown', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000009, 'Black', ROUND(2.0 + (RAND() * 2.0), 2)),
(800000009, 'Teal', ROUND(2.0 + (RAND() * 2.0), 2));

SHOW CREATE TABLE colors;

