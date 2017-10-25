<?php

require "config.php";

$dbCon = mysqli_connect('p:'.$myHost, $myUser, $myPass, $myDB);
//        or die("Could not connect to "
//            .$myHost
//            ." using "
//            .$myUser
//            ."/"
//            .$myPass
//            ."<br />"
//            .mysqli_connect_error()
//            ."<br />");
mysqli_set_charset($dbCon, 'utf8');
//mysqli_select_db($dbCon, $myDB)
//        or die("Could not select "
//            .$myDB
//            ." over "
//            .$dbCon
//            ."<br />"
//            .mysqli_error($dbCon)
//            ."<br />");
