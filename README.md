# SQL-PHP-BasketDiscounts
## My Approach
My approach to this challenge was to create a webpage that uses Object-Oriented PHP to communicate and retrieve data on products and relevant discounts through a MySQL database connection. In HTML I created a product gallery area at the bottom of the page to display all products found within the database's products table, and then a shopping basket to store any products that had been added to basket by the user. I then created a CSS file to change the style of the HTML elements, by adding padding, margins, float, colour etc. Both the products and discounts are stored within the database in SQL and they can be edited to allow for an unlimited amount of new product / discount combinations to be added. 
## Building and Running:
### Steps:
- Download the zip file from GitHub containing my project files.
- Download and Install XAMPP for Windows.
- On the XAMPP control panel, enable both the Apache Server and MySQL.
- Navigate to the XAMPP install directory (usually within your computers program files) and place all project files in a new subfolder within the 'htdocs' folder.
- Ensure that the product-images subfolder is within that same folder you just created.
- On the XAMPP control panel, press the 'Admin' button on the MySQL row which should open up phpMyAdmin (localhost/phpmyadmin).
- On the left pane, create a new database by pressing 'New' at the top of the database list, and name it 'basketdiscounts' and press 'Create'.
- Then select 'Import' from the top bar and then 'Choose file'.
- Navigate to the project files and select the file named 'data.sql', then press 'Go'.
- The 'basketdiscounts' database should now have two tables listed underneath it 'tbldiscount' and 'tblproduct'.
- Using a web browser (Chrome, Firefox etc.) enter the following url and ensure you type the correct subfolder name you created within the 'htdocs' folder: localhost/'FOLDERYOUCREATED'/index.php  

All connection variables such as dbname, dbpassword can be changed within the dbcontroller.php file. The host, user and password variables are all set to default so this should not be an issue.  
The project will run on any PHP enabled web server with access to phpMyAdmin or similar, however I have only provided instructions on how to build it using XAMPP, as this is a free and simple alternative to host a local server.   
 
## File List:
- index.php
- dbcontroller.php
- style.css
- icon-delete.png
- data.sql
- product-images/af1.jpg
- product-images/battenberg.jpg
- product-images/dior-fragrance.jpg
- product-images/gaming-laptop.jpg
- product-images/xbox-console.jpg
