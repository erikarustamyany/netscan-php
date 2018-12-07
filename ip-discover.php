<html>
<head>
    <title>
        Discover IP
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
<?php if(!isset($_GET['ip']) || $_GET['ip'] == "") die("IP is empty."); ?>
<span>Discovering...</span>
<br>
<a target=_blank href=http://<?= $_GET['ip'] ?>><?= $_GET['ip'] ?></a>
<?php
$l = getLocationInfo($_GET['ip']);
?>
<p>
    <b>Country: </b> <?php echo $l[0] . ", " . $l[1] ?>
    <br>
    <b>Coordinates: </b> <?php echo $l[4] . ", " . $l[5] ?>
</p>
<hr>
<h3>
    Opened ports
</h3>
<?php
// SET VARS
    ini_set('max_execution_time', 0);

$ports = array(20, 21, 22, 23, 25, 53, 67, 68, 69, 80, 110, 123, 137, 138, 139, 143, 161, 162, 179, 389, 443, 636, 989, 990, 11211);
$subnets = array("130.193.122.");
$subnet_last = null;
$from_port = 0;
$to_port = 255;

$subnets = [];
$subnets[] = $_GET['first'];
$from_port = intval($_GET['from']);
$to_port = intval($_GET['to']);

    if(!empty($_GET['last'])){
        $subnet_last = $_GET['last'];
    }

// driver
    check_sub($subnets, $ports, $from_port, $to_port);

// Print table
function check_sub($sub, $ports, $fport, $tport){
        $ips = $_GET['ip'];
            flush(); ob_flush();
            foreach($ports as $key => $port) {
                if (@fsockopen($ips, $port, $errno, $errstr, 2)) {
                    $connection = "<b>" . $port . " opened" . "</b>";
                } else {
                    $connection = $port . " closed";
                }
                echo "<span>$connection</span>";
                echo "<br>";
            }
        flush();
        ob_flush();
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
