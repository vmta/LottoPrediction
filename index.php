<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Lotto Prediction</title>
        <link type="text/css" rel="stylesheet" href="css/main.css" />
        <script>
            function myFunction() {
                var x = document.getElementById("myTopnav");
                if (x.className === "topnav") {
                    x.className += " responsive";
                } else {
                    x.className = "topnav";
                }
            }
        </script>
    </head>
    <body>

<?php
if(!function_exists('classAutoLoader')){
    function classAutoLoader($class){
        $classFile='class/'.$class.'.php';
        if(is_file($classFile)&&!class_exists($class)) include $classFile;
    }
}
spl_autoload_register('classAutoLoader');

require "constants.php";

//global $myHost, $myUser, $myPass, $myDB, $dbCon;
//require "db/config.php";
//require "db/connect.php";

include "navigation/topnav.php";
if(isset($_GET['cat'])) {
    include "navigation/subnav.php";
    echo getSubnav($_GET['cat']);
}
?>

<div class="container">
<?php
if(isset($_GET['cat'])) {
    $file = $_GET['cat'].".php";
    include $file;
} else {
    include "home/displayJackPot.php";
    include "home/displayLastGames.php";
    $data = "<h2>" . CAT_HOME . "</h2>";
#    $data .= displayJackPot();
    $data .= displayJackPot(5, 50);
    $data .= displayLastGames(10, true);
    echo $data;
}
?>
</div>

<?php
//mysqli_close($dbCon);
echo "<div class=\"footer\">&copy; " . date("Y") . " vmta</div>";
?>

</body>
</html>
