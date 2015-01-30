# securePHPLogin
my first php project. target is a simple but secure login free to use one every hp. 
similar to https://github.com/peredurabefrog/phpSecureLogin
built after http://de.wikihow.com/Ein-sicheres-Login-Skript-mit-PHP-und-MySQL-erstellen

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
- add test user to table:
```sql
INSERT INTO `secure_login`.`members` VALUES(1, 'test_user', 'test@example.com',
'00807432eae173f652f2064bdca1b61b290b52d40e429a7d295d76a71084aa96c0233b82f1feac45529e0726559645acaed6f3ae58a286b9f075916ebf66cacc',
'f9aab579fc1b41ed0c44fe4ecdbfcdb4cb99b9023abb241a6db833288f4eea3c02f76e0d35204a8695077dcf81932aa59006423976224be0390395bae152d4ef');
```
- save the *includes*-folder on your webserver always out of reach of the normal root directory, dont forget to update the include-statements.

