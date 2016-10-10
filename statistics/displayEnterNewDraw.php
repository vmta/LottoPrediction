<?php
function displayEnterNewDraw() {
    $str = "<p>Внести данные последнего розыграша</p>
        <form method='post' action='index.php?cat=statistics&subcat=processEnterNewDraw' accept-charset='utf-8'>
        <input type=text name=drawDate value='" . date('Y-m-d') . "'> Дата розыграша ГГГГ-ММ-ДД<br />
        <input type=text name=drawMachine value=''> Лототрон<br />
        <input type=text name=setOfBalls value='1'> Комплект шаров<br />
        <input type=text name=drawNums value='1;2;3;4;5;6'> 6 номеров разделенных точкой с запятой<br />
        <input type=text name=guess_2 value='0'> количество угадавших 2x номера<br />
        <input type=text name=award_2 value='0'> выигрыш за 2x номера<br />
        <input type=text name=guess_3 value='0'> количество угадавших 3x номера<br />
        <input type=text name=award_3 value='0'> выигрыш за 3x номера<br />
        <input type=text name=guess_4 value='0'> количество угадавших 4x номера<br />
        <input type=text name=award_4 value='0'> выигрыш за 4x номера<br />
        <input type=text name=guess_5 value='0'> количество угадавших 5x номеров<br />
        <input type=text name=award_5 value='0'> выигрыш за 5x номеров<br />
        <input type=text name=guess_6 value='0'> количество угадавших 6x номеров<br />
        <input type=text name=award_6 value='0'> выигрыш за 6x номеров<br />
        <input type=submit value=Ввод> <input type=reset value=Сброс>
        </form>";
    return $str;
}