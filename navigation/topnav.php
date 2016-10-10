<?php
$str = "<ul class=\"topnav\" id=\"myTopnav\">
    <li><a class=\"active\" href=\"index.php\">" . CAT_HOME . "</a></li>
    <li><a href=\"?cat=statistics\">" . CAT_STATISTICS . "</a></li>
    <li><a href=\"?cat=analysis\">" . CAT_ANALYSIS . "</a></li>
    <li><a href=\"?cat=prediction\">" . CAT_PREDICTION . "</a></li>
    <li class=\"icon\">
    <a href=\"javascript:void(0);\" style=\"font-size:15px;\" onclick=\"myFunction()\">☰</a>
    </li>
    </ul>";

echo $str;