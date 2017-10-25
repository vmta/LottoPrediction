<?php
function processCheckTicket($drawDate, $drawID, $drawNums, $iterationDepth) {
    // Split and sort nums
    $nArr = explode(";", $drawNums);
    sort($nArr);
    $query = "SELECT
            `ball_1`,
            `ball_2`,
            `ball_3`,
            `ball_4`,
            `ball_5`,
            `ball_6`
            FROM ";
    if($iterationDepth > 1) {
        $query .= "(";
        for($i = $drawID; $i > ($drawID - $iterationDepth); $i--) {
            $query .= "SELECT 
                        `ball_1`,
                        `ball_2`,
                        `ball_3`,
                        `ball_4`,
                        `ball_5`,
                        `ball_6`
                    FROM `full`
                    WHERE `id` = " . $i;
            if($i > ($drawID - $iterationDepth + 1)) {
                $query .= " UNION ALL ";
            }
        }
        $query .= ") AS t1";
    } else {
        $query .= "`full`"
                . (empty($drawID) ?
                    (empty($drawDate) ?
                            ""
                            : " WHERE `date` LIKE '" . $drawDate . "'")
                    : " WHERE `id` = " . $drawID);
    }
    $q_res = mysqli_query($query)
            or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
    $str = "";
    if(mysqli_num_rows($q_res)) {
        while($row = mysqli_fetch_array($q_res, MYSQL_ASSOC)){
            $hitsNumber = 0;
            $str .= "<div width='100%' style='display: table;'>";
            $str .= "<h2>Winning combination for draw " . $drawID . "</h2>";
            foreach($row as $ball) {
                $ballClass = "ballMISS";
                foreach($nArr as $num) {
                    if($ball == $num) {
                        $ballClass = "ballHIT";
                        $hitsNumber++;
                    }
                }
                $str .= "<div class='" . $ballClass . "'><h3>" . $ball . "</h3></div>";
            }
            $str .= "</div>";
            if($hitsNumber > 1) {
                $query1 = "SELECT `award_" . $hitsNumber . "` "
                        . "FROM `full` WHERE `id`="
                        . $drawID;
                $q_res1 = mysqli_query($query1)
                        or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
                $row1 = mysqli_fetch_row($q_res1);
                $award = $row1[0];
                $str .= "<p>You've guessed <b>"
                        . $hitsNumber
                        . "</b> numbers out of 6 "
                        . "possible and won <b>"
                        . (($hitsNumber < 2) ? "nothing" : $award)
                        . "</b>.</p>";
                mysqli_free_result($q_res1);
            }
            $drawID--;
        }
        $str .= "<div width='100%' style='display: table;'>";
        $str .= "<h2>Your combination</h2>";
        foreach($nArr as $num) {
            $ballClass = "ballMISS";
            $str .= "<div class='" . $ballClass . "'><h3>" . $num . "</h3></div>";
        }
        $str .= "</div>";
    }
    mysqli_free_result($q_res);
    return $str;
}
