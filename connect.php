<?php
 //database login details
 DEFINE (constant_name:'DB_User', value: 'root');
 DEFINE (constant_name: 'DB_Password', value: '');
 DEFINE (constant_name: 'DB_Host', value: 'localhost');
 DEFINE (constant_name: 'DB_Name', value:'horizon');
 
 //make connection to the database
 $DBCONN = mysqli_connect(hostname:DB_Host,password:DB_Password,username:DB_User,database:DB_Name);
 
 //check connection')
 if(!$DBCONN) {
    die('failed to connect: ' . mysqli_connect_error()); 
 }

 ?>