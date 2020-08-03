# CartSample
A sample application to allow Booking of Lab Test/ Book Lab Test Package. Lab tests/packages are treated as items.

## First Page - Login Page
URL- http://localhost/CartSample/Login

After Loggin in Click on Items in the Menu, add items to cart, and then place order.
Cart data will be saved in Redis.

## MySQL DB schema and connection
For DB schema and populating master tables, run the .sql file oms.sql kept in system/database. Update db credentials in application/config/database.php

## Login Credentials
3 login credentials have been created which are as follows:-
1) Username- root, Password- password
2) Username- test, Password- test123
3) Username- admin, Password- admin

More users can be added in the tbl_users table manually. The password field's value can be generated through the below PHP code: 

$hash = password_hash($password, PASSWORD_DEFAULT);