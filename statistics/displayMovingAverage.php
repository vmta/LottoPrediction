<?php
function displayMovingAverage() {
    $str = "Укажите параметры для отображения данных: количество угаданых номеров "
            . "<form method='post' action='index.php?cat=statistics&subcat=processMovingAverage'>"
            . "<select name='opt'>"
            . "<option value='All' selected>Все</option>"
            . "<option value='6x'>6x</option>"
            . "<option value='5x'>5x</option>"
            . "<option value='4x'>4x</option>"
            . "<option value='3x'>3x</option>"
            . "</select>"
            . " в "
            . "<input type='text' name='draws' size='4' value='100'>"
            . " играх, на лототроне "
            . "<select name='drawmachine'>"
            . "<option value='All' selected>Все</option>"
            . "<option value='А'>А</option>"
            . "<option value='Б'>Б</option>"
            . "</select>"
            . " с набором шаров "
            . "<select name='setofballs'>"
            . "<option value='All' selected>Все</option>"
            . "<option value='1'>1</option>"
            . "<option value='2'>2</option>"
            . "<option value='3'>3</option>"
            . "<option value='4'>4</option>"
            . ", аггрегатор игр для расчета SMA/WMA:"
            . "<input type='text' name='aggregator' size='4' value='5'>. "
            . "<input type='submit' value='show'>"
            . "</form>";
    
    $str .= "<p>Построить графики SMA/WMA используя следующие параметры:<br />
            - количество игр<br />
            учитывать игры в которых было выиграно:<br />
            - 2х номера<br />
            - 3х номера<br />
            - 4х номера<br />
            - 5х номеров<br />
            - 6х номеров<br />
            только на лототроне<br />
            - А<br />
            - Б<br />
            только при наборе шаров<br />
            - 1<br />
            - 2<br />
            - 3<br />
            - 4<br />
            </p>";
    
    return $str;
}