<?php

function displaySeries() {
    $script = "<script src=\"https://code.jquery.com/jquery-3.1.1.min.js\"
            integrity=\"sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=\"
            crossorigin=\"anonymous\"></script>
            
            <script src=\"js/jquery.dataTables.min.js\"></script>";
    $arrayWins = getArrayWins();
    $arrayNoWins = getArrayNoWins();
    $str = $script . "<center>
            <div style=\"width:400px;padding:10px;border:1px solid #aaaaaa;\">
            
            <table id=\"dTab\" class=\"display\">
            
            <thead><tr>
            <th>Всего выпадал</th>
            <th>Номер</th>
            <th>Не выпадал</th>
            </tr></thead>
            
            <tfoot><tr>
            <th>Всего выпадал</th>
            <th>Номер</th>
            <th>Не выпадал</th>
            </tr></tfoot>
            
            <tbody>";
    foreach(array_keys($arrayWins) as $key) {
        $str .= "<tr>"
                . "<td data-order=\"$arrayWins[$key]\"><div style=\"width: " . (100 * $arrayWins[$key] / max($arrayWins)) . "%;background: olive;float: right;text-align: right;\">" . $arrayWins[$key] . "</div></td>"
                . "<td data-search=\"$key\" data-order=\"$key\" align=\"center\"><div style=\"float: center;border:1px solid #aaaaaa;\">" . $key . "</div></td>"
                . "<td data-order=\"$arrayNoWins[$key]\"><div style=\"width: " . (100 * $arrayNoWins[$key] / max($arrayNoWins)) . "%;background: orange;\">" . $arrayNoWins[$key] . "</div></td>"
                . "</tr>";
    }
    $str .= "</tbody>
            </table>
            </div>
            </center>
            <script>
            $(document).ready(function() {
                $('#dTab').DataTable();
            } );
            </script>";
    return $str;
}

function queryConstructor($type, $key) {
    $query = "";
    switch($type) { /* id > 796 */
        case "Wins":
            $query = "SELECT
                COUNT(*)
                FROM `full`
                WHERE
                `id` > 796 AND (
                `ball_1` = " . $key . " OR
                `ball_2` = " . $key . " OR
                `ball_3` = " . $key . " OR
                `ball_4` = " . $key . " OR
                `ball_5` = " . $key . " OR
                `ball_6` = " . $key . ");";
            break;
        case "NoWins":
            $query = "SELECT
                `id`,
                `ball_1`,
                `ball_2`,
                `ball_3`,
                `ball_4`,
                `ball_5`,
                `ball_6`
                FROM `full`
                WHERE
                `ball_1` = " . $key . " OR
                `ball_2` = " . $key . " OR
                `ball_3` = " . $key . " OR
                `ball_4` = " . $key . " OR
                `ball_5` = " . $key . " OR
                `ball_6` = " . $key . "
                ORDER BY `id` DESC LIMIT 1;";
            break;
    }
    return $query;
}

function getCurrentDrawNumber() {
    $q = "SELECT MAX(`id`) FROM `full`;";
    $q_res = mysqli_query($q)
            or die("Could not perform ".$q."<br />".mysqli_error()."<br />");
    $row = mysqli_fetch_row($q_res);
    return $row[0];
}

function createArray() {
    $arr = array();
    for($i = 1 ; $i <= 52; $i++) {
        $arr[$i] = 0;
    }
    return $arr;
}

function getArrayWins() {
    $arr = createArray();
    foreach(array_keys($arr) as $key) {
        $query = queryConstructor("Wins", $key);
        $q_res = mysqli_query($query)
                or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
        $row = mysqli_fetch_row($q_res);
        $arr[$key] = $row[0];
    }
    return $arr;
}

function getArrayNoWins() {
    $currentDrawNumber = getCurrentDrawNumber();
    $arr = createArray();
    foreach(array_keys($arr) as $key) {
        $query = queryConstructor("NoWins", $key);
        $q_res = mysqli_query($query)
                or die("Could not perform ".$query."<br />".mysqli_error()."<br />");
        $row = mysqli_fetch_row($q_res);
        $arr[$key] = $currentDrawNumber - $row[0];
    }
    arsort($arr);
    return $arr;
}
