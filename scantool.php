<html>
<head>
    <title>
        Network Scan-Tool
    </title>

    <style>
        tbody {
            text-align: center;
            width: 100%;
        }
        table {
            width: 100%;
        }

        td {
            display: block;
        }
        tr {
            padding: 5px;
            display: inline-block;
            border: 1px solid darkgrey;
            width: 14%;
        }
        input {
            margin: 0 5px 5px;
        }
        input[type="submit"]{
            background-color: ghostwhite;
            width: 350px;
            margin: 0;
            padding: 7px;
        }
        .centered {
            text-align: center;
        }
        a{
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>


<?php
// SET VARS
error_reporting(0);
    ini_set('max_execution_time', 0);

include("counter.php");

$ports = array(80,443, 11211);
$subnets = array("130.193.122.");
$subnet_last = null;
$from_port = 0;
$to_port = 255;

// Search FORM
echo "<div class='centered'><form action=\"scantool.php\" method=GET>" .
    "First part:    <input type=text name=first value='" . $_GET['first'] ."'> <br>" .
    "Last part:     <input type=text name=last value='" . $_GET['last'] ."'> <br>" .
    "From:          <input type=number name=from value='" . $_GET['from'] ."'> <br>" .
    "To:            <input type=number name=to value='" . $_GET['to'] ."'> <br>" .
    "Scanning ports:<input type=text name=ports value='" . $_GET['ports'] ."'> <br>" .
    "<input type=submit></form></div>";

if(isset($_GET['from']) && isset($_GET['to'])){
    $ports = explode(',', trim($_GET['ports']));
    $subnets = [];
    $subnets[] = $_GET['first'];
    $from_port = intval($_GET['from']);
    $to_port = intval($_GET['to']);

    if(!empty($_GET['last'])){
        $subnet_last = $_GET['last'];
    }

// driver
    check_sub($subnets, $ports, $from_port, $to_port, $subnet_last);
}


// Print table
function check_sub($sub, $ports, $fport, $tport, $ips_end){
    echo "<table bordercolor=#eee cellspacing=0>";
    $j=0;
    for($i=$fport; $i<$tport; $i++) {
        echo "<tr>";
        foreach($sub as $ips) {
                $end = $i+$j;
                $ip=$ips.$end . $ips_end;
                flush(); ob_flush();
                echo "<td><a target=_blank href=\"/ip-discover.php?ip=$ip\">More</a></td>";
                echo "<td><a target=_blank href=http://$ip>$ip</a></td>";
                foreach($ports as $key => $port){
                    if(@fsockopen($ip,$port,$errno,$errstr,2)){
                        $connection = "<b>" . $port  . " opened" . "</b>";
                    } else {
                        $connection =  $port . " closed";
                    }
                    echo "<td>$connection</td>";
                }

                echo "<td width=50> &nbsp; &nbsp; </td>";

            echo "</tr>";
        }
        flush();
        ob_flush();
    }
    echo "</table>";
}

function getLocationInfo($url) {
    $searchurl = "http://api.locatorhq.com/?user=chris7665&key=851b79ea8c8d2249dbe47d6830ee87fa6f31d265&ip=";
    $ch_ = curl_init();
    curl_setopt($ch_, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_, CURLOPT_URL,$searchurl.$url);
    $result=curl_exec($ch_);
    curl_close($ch_);
    return explode(",",$result);
}
?>
</body>
</html>

