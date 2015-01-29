# securePHPLogin
my first php project. target is a simple but secure login free to use one every hp.

# setup
- xampp server
- sql root user 
- sql db
- the following table:
```sql
CREATE USER 'sec_user'@'localhost' IDENTIFIED BY 'eKcGZr59zAa2BEWU';
GRANT SELECT, INSERT, UPDATE ON `secure_login`.* TO 'sec_user'@'localhost'
```