<?php
function displayCheckTicket($drawDate, $drawID, $drawNums, $iterationDepth) {
    
    $query = "SELECT "
            . "`id`, "
            . "`date`, "
            . "`ball_1`, "
            . "`ball_2`, "
            . "`ball_3`, "
            . "`ball_4`, "
            . "`ball_5`, "
            . "`ball_6` "
            . "FROM `full` "
            . "WHERE `id` = (SELECT MAX(`id`) FROM `full`)";
    $q_res = mysql_query($query)
            or die("Could not perform ".$query."<br />".mysql_error()."<br />");
    $row = mysql_fetch_row($q_res);
    if(empty($drawID)) {
        $drawID = $row[0];
    }
    if(empty($drawDate)) {
        $drawDate = $row[1];
    }
    if(empty($drawNums)) {
        $drawNums = $row[2] . ";"
            . $row[3] . ";"
            . $row[4] . ";"
            . $row[5] . ";"
            . $row[6] . ";"
            . $row[7];
    }
    mysql_free_result($q_res);
    
    $str = "<p>Введите комбинацию цифр, разделенных точкой с запятой</p>
        <form method='post' action='index.php?cat=analysis&subcat=processCheckTicket' accept-charset='utf-8'>
        <input type=text name=drawDate value=" . $drawDate . "> Select date of the draw, or<br />
        <input type=text name=drawID value=" . $drawID . "> Select the draw number<br />
        <input type=text name=drawNums value=" . $drawNums . "> 6 numbers divided by ;<br />
        <input type=text name=iterationDepth value=" . $iterationDepth . "> How many games to traverse?<br />
        <input type=submit value=Proceed> <input type=reset value=Clear>
        </form>";
    
    return $str;
}