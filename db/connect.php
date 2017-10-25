<?php
$dbCon = mysqli_connect($myHost, $myUser, $myPass, $myDB)
        or die("Could not connect to "
            .$myHost
            ." using "
            .$myUser
            ."/"
            .$myPass
            ."<br />"
            .mysqli_error()
            ."<br />");
#mysqli_set_charset('utf8', $dbCon);
#mysqli_select_db($myDB, $dbCon)
#        or die("Could not select "
#            .$myDB
#            ." over "
#            .$dbCon
#            ."<br />"
#            .mysqli_error()
#            ."<br />");
