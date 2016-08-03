CREATE USER 'secure_login'@'localhost' IDENTIFIED BY 'PASSWORTinLASTPASS';
GRANT SELECT, INSERT, UPDATE ON `secure_login_v2`.* TO 'secure_login'@'localhost';

