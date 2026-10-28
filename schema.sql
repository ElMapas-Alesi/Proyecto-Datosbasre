CREATE DATABASE IF NOT EXISTS escuela_db;
USE escuela_db;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    age INT NOT NULL
);

INSERT INTO students (first_name, last_name, age) VALUES
('Carlos', 'Gómez', 21),
('María', 'Rodríguez', 22),
('Juan', 'Pérez', 20),
('Laura', 'Martínez', 23);
