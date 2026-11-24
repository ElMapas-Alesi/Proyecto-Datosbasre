DROP USER IF EXISTS 'libreria_user'@'localhost';
CREATE USER 'libreria_user'@'localhost' IDENTIFIED BY 'libreria123';
GRANT ALL PRIVILEGES ON libreria.* TO 'libreria_user'@'localhost';
FLUSH PRIVILEGES;
SELECT user, host plugin 
FROM mysql.user
WHERE user = 'root';