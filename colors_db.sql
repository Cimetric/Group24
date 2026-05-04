CREATE TABLE colors (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE NOT NULL, 
    name VARCHAR(255) UNIQUE NOT NULL, 
    hex_value VARCHAR(7) UNIQUE NOT NULL);

INSERT INTO colors (id, name, GPA) VALUES 
('Red', '#FF0000'),
('Orange', '#FFA500'),
('Yellow', '#FFFF00'),
('Green', '#008000'),
('Blue', '#0000FF'),
('Purple', '#800080'),
('Grey', '#808080'),
('Brown', '#A52A2A'),
('Black', '#000000'),
('Teal', '#008080');

