<?php
function displayPerformanceVariations() {
    $str = "Укажите параметры для отображения данных:<br />"
            . "<form method='post' action='index.php?cat=statistics&subcat=processPerformanceVariations'>"
            . "Количество угаданых номеров в "
            . "<input type='text' name='draws' size='4' value='50'>"
            . " играх, на "
            . "<select name='drawmachine'>"
            . "<option value='All' selected>любом</option>"
            . "<option value='А'>А</option>"
            . "<option value='Б'>Б</option>"
            . "</select>"
            . " лототроне с "
            . "<select name='setofballs'>"
            . "<option value='All' selected>любым</option>"
            . "<option value='1'>1</option>"
            . "<option value='2'>2</option>"
            . "<option value='3'>3</option>"
            . "<option value='4'>4</option>"
            . "</select>"
            . " набором шаров.<br />"
            . "Период аггрегации игр для расчета скользящего среднего: "
            . "<input type='text' name='aggregator' size='4' value='5'>"
            . " для "
            . "<select name='groupID'>"
            . "<option value='All' selected>всех</option>"
            . "<option value='1'>1ой</option>"
            . "<option value='2'>2ой</option>"
            . "<option value='3'>3ей</option>"
            . "<option value='4'>4ой</option>"
            . "<option value='5'>5ой</option>"
            . "<option value='6'>6ой</option>"
            . "</select>"
            . " серии номеров.<br />"
            . "Рассчитать тренд "
            . "<select name='algorithm'>"
            . "<option value=\"Linear\" selected>линейным</option>"
            . "</select>"
            . " методом.<br />"
            . "Окно прогноза: "
            . "<select name='forecastFrame'>"
            . "<option value='1'>1</option>"
            . "<option value='2'>2</option>"
            . "<option value='3'>3</option>"
            . "<option value='4'>4</option>"
            . "<option value='5'>5</option>"
            . "</select>"
            . " игр.<br />"
            . "<input type='submit' value='show'>"
            . "</form>";
    
    return $str;
}