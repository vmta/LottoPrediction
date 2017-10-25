<?php
function displayBernulli() {
    $str = "<p>Вероятность "
            . "P<sup>m</sup><sub>n</sub>="
            . "C<sup>m</sup><sub>n</sub>"
            . "p<sup>m</sup>"
            . "q<sup>n-m</sup>"
            . ", где<br />"
            . "p - статистическая вероятность появления события A в каждом испытании;<br />"
            . "q - вероятность непоявления события А в каждом испытании (равна 1 - p);<br />"
            . "C<sup>m</sup><sub>n</sub> - биноминальный коэффициент "
            . "(количество вариантов выбора без возвращения и без учета порядка, "
            . "равен n!/((n-m)! * m!).</p>"
            . "<p>Исходя из указанного, рассчитаем вероятность наступления события P(A) для "
            . "выбранных событий на основании установленной статистической вероятности в указанном "
            . "количестве игр.</p>";
    
    $str .= "<form method='post' action='index.php?cat=analysis&subcat=processBernulli' accept-charset='utf-8'>"
            . "<p>Рассчитываем вероятность для игр, в которых было угадано: "
            . "<select name='opt'>"
            . "<option value='All' selected>All</option>"
            . "<option value='6x'>6x</option>"
            . "<option value='5x'>5x</option>"
            . "<option value='4x'>4x</option>"
            . "<option value='3x'>3x</option>"
            . "</select>"
            . " номеров в "
            . "<input type='text' name='draws' size='5' value='100'>"
            . " играх для лототрона "
            . "<select name='drawmachine'>"
            . "<option value='All' selected>All</option>"
            . "<option value='А'>А</option>"
            . "<option value='Б'>Б</option>"
            . "</select>"
            . " и набора шаров "
            . "<select name='setofballs'>"
            . "<option value='All' selected>All</option>"
            . "<option value='1'>1</option>"
            . "<option value='2'>2</option>"
            . "<option value='3'>3</option>"
            . "<option value='4'>4</option>"
            . ". "
            . "<input type='submit' value='show'>"
            . "</p></form>";
    
    return $str;
}