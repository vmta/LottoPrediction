<?php
function displayProbability() {
    $str = "<p>Вероятность наступления события P(A) по формуле P(A)=m/n "
            . "(где n - количество возможных положительных исходов, "
            . "а m - общее количество возможных исходов) "
            . "в контексте угадывания одного номера из 52 равна примерно "
            . "<b>" . getProb(1, 52) . "%</b>. "
            . "Рассчитаем количество комбинаций C(A) по формуле C(A) = n!/((n-m)!*m!) "
            . "и вероятности наступления события.</p>";
    
    $str .= "<table class=\"probability\">
    <tr>
    <th>Событие</th><th>Комбинаций</th><th>Вероятность</th>
    </tr>";
    for($i = 1; $i < 7; $i++) {
        $str .= "<tr><td>" . $i . "x</td><td class=\"combinations\">" . getComb($i, 52) . "</td><td class=\"percentage\">" . getProb($i, 52) . "</td></tr>";
    }
    $str .= "</table>";
    
    return $str;
}

function getComb($m, $n) {
    return (new Combination($m, $n))->calculate();
}

function getProb($m, $n) {
    return (100 * (new Probability($m, $n))->calculateSimple()) . "%";
}