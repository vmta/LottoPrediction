<?php
function displayMinMaxAvg() {
    $str = "Select which data to display (guessed numbers per amount of games): "
            . "<form method='post' action='index.php?cat=statistics&subcat=processMinMaxAvg'>"
            . "<select name='opt'>"
            . "<option value='All' selected>All</option>"
            . "<option value='6x'>6x</option>"
            . "<option value='5x'>5x</option>"
            . "<option value='4x'>4x</option>"
            . "<option value='3x'>3x</option>"
            . "</select>"
            . " numbers in "
            . "<input type='text' name='draws' size='5' value='100'>"
            . " games per draw machine "
            . "<select name='drawmachine'>"
            . "<option value='All' selected>All</option>"
            . "<option value='А'>А</option>"
            . "<option value='Б'>Б</option>"
            . "</select>"
            . " per set of balls "
            . "<select name='setofballs'>"
            . "<option value='All' selected>All</option>"
            . "<option value='1'>1</option>"
            . "<option value='2'>2</option>"
            . "<option value='3'>3</option>"
            . "<option value='4'>4</option>"
            . ". "
            . "<input type='submit' value='show'>"
            . "</form>";
    
    return $str;
}