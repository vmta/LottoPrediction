<?php
/**
 * Description of Chart
 * 
 * @author vmta
 */
class Chart {
    
    public function __construct() {}
    
    public function draw($coords) {
        $chartWidth = 400;
        $chartWidthMultiplicator = round($chartWidth/count($coords));
        $chartHeight = 200;
        $chartHeightMultiplicator = round($chartHeight/count($coords));
        $str = "<canvas style='border:1px solid black;' width='" . $chartWidth
                . "' height='" . $chartHeight
                . "' id='Canvas'></canvas>";
	$str .= "<script>";
	$str .= "var canvas = document.getElementById('Canvas');";
	$str .= "var ctx = canvas.getContext('2d');";
	$str .= "ctx.moveTo(0, 100);";
	for($i = 0; $i < count($coords) - 1; $i++) {
		$str .= "ctx.lineTo("
                        . ($i * $chartWidthMultiplicator)
                        . ", "
                        . ($chartHeight - $coords[$i] * $chartHeightMultiplicator)
                        . ");";
	}
	$str .= "ctx.stroke();";
	$str .= "</script>";
	
	return $str;
    }
    public function redraw() {}
    
    public function gDraw($chartData) {
        
        // Prepare rows to add to data
        $preparedRows = "";
        $countedRows = count($chartData);
        $counter = 0;
        
        foreach($chartData as $row) {
            $preparedRows .= "[ "
                            . $counter
                            . ", "
                            . $row[1]
                            . ", "
                            . $row[2]
                            . ", "
                            . $row[3]
                            . ", "
                            . $row[4]
                            . ", "
                            . $row[5]
                            . ", "
                            . $row[6]
                            . " ]";
            if($counter < $countedRows - 1) {
                $preparedRows .= ", ";
            }
            $counter++;
        }
        
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
                <div id=\"chart_div\" style=\"width: 800; height: 600px;\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'N');
                      data.addColumn('number', 'G1');
                      data.addColumn('number', 'G2');
                      data.addColumn('number', 'G3');
                      data.addColumn('number', 'G4');
                      data.addColumn('number', 'G5');
                      data.addColumn('number', 'G6');

                      data.addRows([" . $preparedRows . "]);

                      var options = {
                        hAxis: {
                          title: 'Time',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Popularity',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#aaee99', '#aaaaee', '#66ccee', '#66aacc', '#9966ee', '#ee66cc'],
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        title: 'Серии номеров'
                      };
                      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
    
    public function gDrawWithTrendline($chartData) {
        
        // Get a groupID which is an innerKey
        // of a 2-dimensional array.
    //    $groupID = array_shift(array_keys($chartData[0]));
        
        // Prepare rows to add to data
        $preparedRows = "";
        $countedRows = count($chartData);
        $counter = 0;
        foreach($chartData as $row) {
        //    $preparedRows .= "[ " . $counter . ", " . $row[$groupID] . " ]";
            $preparedRows .= "[ " . $counter . ", " . $row . " ]";
            if($counter < $countedRows - 1) {
                $preparedRows .= ", ";
            }
            $counter++;
        }
        
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
            /*    <div id=\"chart_div" . $groupID . "\"></div> */
                <div id=\"chart_div\" style=\"width: 800; height: 600px;\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'N');
                //      data.addColumn('number', 'Группа " . $groupID . "');
                      data.addColumn('number', 'Группа');

                      data.addRows([" . $preparedRows . "]);

                      var options = {
                        hAxis: {
                          title: 'Draws',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Numbers',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#097138'],
                        trendlines: {
                          0: {type: 'exponential', color: '#333', opacity: 1},
                          1: {type: 'linear', color: '#111', opacity: .3}
                        }
                      };
                    //  var chart = new google.visualization.LineChart(document.getElementById('chart_div" . $groupID . "'));
                      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
    
    public function gDrawGROUP($chartData, $groupID) {
        
        // Prepare rows to add to data
        $preparedRows = "";
        $countedRows = count($chartData);
        $counter = 0;
        foreach($chartData as $row) {
            $preparedRows .= "[ " . $counter . ", " . $row . " ]";
            if($counter < $countedRows - 1) {
                $preparedRows .= ", ";
            }
            $counter++;
        }
        
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
                <div id=\"group" . $groupID . "\" style=\"width: 400; height: 300px;\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'N');
                      data.addColumn('number', 'Группа " . $groupID . "');
                      data.addRows([" . $preparedRows . "]);

                      var options = {
                        hAxis: {
                          title: 'Draws',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Numbers',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#cc9966'],
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        title: 'Выпавшие в группе номера'
                      };
                      var chart = new google.visualization.LineChart(document.getElementById('group" . $groupID . "'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
    
    public function gDrawSMA($chartData, $groupID, $aggregator) {
        
        // Prepare rows to add to data
        $preparedRows = "";
        $countedRows = count($chartData);
        $counter = 0;
        foreach($chartData as $row) {
            $preparedRows .= "[ " . $counter . ", " . $row . " ]";
            if($counter < $countedRows - 1) {
                $preparedRows .= ", ";
            }
            $counter++;
        }
        
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
                <div id=\"sma" . $groupID . $aggregator . "\" style=\"width: 400; height: 300px;\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'N');
                      data.addColumn('number', 'Группа " . $groupID . "');
                      data.addRows([" . $preparedRows . "]);

                      var options = {
                        hAxis: {
                          title: 'Draws',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Numbers',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#6699cc'],
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        title: 'SMA (" . $aggregator . " draws)'
                      };
                      var chart = new google.visualization.LineChart(document.getElementById('sma" . $groupID . $aggregator . "'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
    
    public function gDrawWMA($chartData, $groupID, $aggregator) {
        
        // Prepare rows to add to data
        $preparedRows = "";
        $countedRows = count($chartData);
        $counter = 0;
        foreach($chartData as $row) {
            $preparedRows .= "[ " . $counter . ", " . $row . " ]";
            if($counter < $countedRows - 1) {
                $preparedRows .= ", ";
            }
            $counter++;
        }
        
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
                <div id=\"wma" . $groupID . $aggregator . "\" style=\"width: 400; height: 300px;\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'N');
                      data.addColumn('number', 'Группа " . $groupID . "');
                      data.addRows([" . $preparedRows . "]);

                      var options = {
                        hAxis: {
                          title: 'Draws',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Numbers',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#cc9966'],
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        title: 'WMA (" . $aggregator . " draws)'
                      };
                      var chart = new google.visualization.LineChart(document.getElementById('wma" . $groupID . $aggregator . "'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
    
    public function gDrawGroupWithSMA($chartData, $sma, $groupID) {
        
        // Prepare rows to add to data
        $preparedRows = "";
        $countedRows = count($chartData);
        $counter = 0;
        foreach($chartData as $row) {
            $preparedRows .= "[ " . $counter . ", " . $row . " ]";
            if($counter < $countedRows - 1) {
                $preparedRows .= ", ";
            }
            $counter++;
        }
        
        $str .= "<script type=\"text/javascript\"
                src=\"https://www.gstatic.com/charts/loader.js\"></script>
                <div id=\"group" . $groupID . "\" style=\"width: 400; height: 300px;\"></div>
                
                <script>
                google.charts.load('current', {packages: ['corechart', 'line']});
                google.charts.setOnLoadCallback(drawAxisTickColors);

                function drawAxisTickColors() {
                      var data = new google.visualization.DataTable();
                      data.addColumn('number', 'N');
                      data.addColumn('number', 'Группа " . $groupID . "');
                      data.addRows([" . $preparedRows . "]);

                      var options = {
                        hAxis: {
                          title: 'Draws',
                          textStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          },
                          titleTextStyle: {
                            color: '#01579b',
                            fontSize: 10,
                            fontName: 'Arial',
                            bold: false,
                            italic: false
                          }
                        },
                        vAxis: {
                          title: 'Numbers',
                          textStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          },
                          titleTextStyle: {
                            color: '#1a237e',
                            fontSize: 12,
                            bold: false
                          }
                        },
                        colors: ['#cc9966'],
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        title: 'Выпавшие в группе номера'
                      };
                      var chart = new google.visualization.LineChart(document.getElementById('group" . $groupID . "'));
                      chart.draw(data, options);
                    }
                </script>
                ";
        return $str;
    }
}