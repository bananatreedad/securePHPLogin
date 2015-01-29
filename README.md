# securePHPLogin
my first php project. target is a simple but secure login free to use one every hp.

# setup
- xampp server
- sql root user 
- create sql db:
```sql
CREATE DATABASE `secure_login`;
```
- create the following tables:
```sql
CREATE TABLE `secure_login`.`members` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(30) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `password` CHAR(128) NOT NULL,
    `salt` CHAR(128) NOT NULL 
) ENGINE = InnoDB;

CREATE TABLE `secure_login`.`login_attempts` (
    `user_id` INT(11) NOT NULL,
    `time` VARCHAR(30) NOT NULL
) ENGINE=InnoDB
```