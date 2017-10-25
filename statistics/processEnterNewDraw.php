<?php
function processEnterNewDraw() {
    $date = $_POST['drawDate'];
    $draw_machine = $_POST['drawMachine'];
    $set_of_balls = $_POST['setOfBalls'];
    $nums = $_POST['drawNums'];
    $guess_2 = $_POST['guess_2'];
    $award_2 = $_POST['award_2'];
    $guess_3 = $_POST['guess_3'];
    $award_3 = $_POST['award_3'];
    $guess_4 = $_POST['guess_4'];
    $award_4 = $_POST['award_4'];
    $guess_5 = $_POST['guess_5'];
    $award_5 = $_POST['award_5'];
    $guess_6 = $_POST['guess_6'];
    $award_6 = $_POST['award_6'];
    
    // Split nums, sort and put numbers into corresponding variables
    $nArr = explode(";", $nums);
    sort($nArr);
    list($ball_1, $ball_2, $ball_3, $ball_4, $ball_5, $ball_6) = $nArr;
    
    $query = "INSERT INTO `full`
            (
            `date`,
            `draw_machine`,
            `set_of_balls`,
            `ball_1`,
            `ball_2`,
            `ball_3`,
            `ball_4`,
            `ball_5`,
            `ball_6`,
            `guess_2`,
            `award_2`,
            `guess_3`,
            `award_3`,
            `guess_4`,
            `award_4`,
            `guess_5`,
            `award_5`,
            `guess_6`,
            `award_6`
            )
            VALUES(
		'" . $date ."',
		'" . $draw_machine ."',
		" . $set_of_balls .",
		" . $ball_1 .",
		" . $ball_2 .",
		" . $ball_3 .",
		" . $ball_4 .",
		" . $ball_5 .",
		" . $ball_6 .",
		" . $guess_2 .",
		" . $award_2 .",
		" . $guess_3 .",
		" . $award_3 .",
		" . $guess_4 .",
		" . $award_4 .",
		" . $guess_5 .",
		" . $award_5 .",
		" . $guess_6 .",
		" . $award_6 ."
            )";
    
    $q_res = mysqli_query($query)
            or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
    mysqli_free_result($q_res);
    
    if(mysqli_error() == "") {
        return "Record " . mysqli_insert_id() . " successfully added.";
    } else {
        return mysqli_error();
    }
}
