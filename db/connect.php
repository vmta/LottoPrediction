<?php
$dbCon = mysql_connect($myHost, $myUser, $myPass)
        or die("Could not connect to "
            .$myHost
            ." using "
            .$myUser
            ."/"
            .$myPass
            ."<br />"
            .mysql_error()
            ."<br />");
mysql_set_charset('utf8', $dbCon);
mysql_select_db($myDB, $dbCon)
        or die("Could not select "
            .$myDB
            ." over "
            .$dbCon
            ."<br />"
            .mysql_error()
            ."<br />");