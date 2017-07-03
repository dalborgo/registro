<html>
<head>
    <style>
        body{
            font-family: sans-serif;
        ;
        }
        table {
            border: 1px solid gray;
            font-size: 11pt
        }

        tbody tr:nth-child(even) {
            background-color: #cbdbef;
        }
        td{
            padding: 2px 5px;
        }
        thead{
            background-color: #1969e6;
            color: white;
        }
        th{
            padding: 5px;
        }
    </style>
</head>
<body>
<tbody>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Asten
 * Date: 13/06/2017
 * Time: 15:50
 */
function query($s, $conn, $sub = false)
{
    if ($sub) {
        $sq = mysqli_query($conn, $s);
        if (!$sq)
            die ('Cannot connect: Query errata');
        return mysqli_fetch_assoc($sq);
    } else {
        $sq = mysqli_query($conn, $s);
        if (!$sq)
            die ('Cannot connect: Query errata');

        return $sq;
    }
}

$host = 'srv2.astenmail.com';
$user = 'ufficio_reg';
$password = 'registro';
$db = 'ufficio_registr';


$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) die('Cannot connect: ' . mysqli_error());


$dr = query("SELECT * FROM registro ORDER BY data", $conn);
$tot = 0;
$out = '';
$i = 0;
while (($h = mysqli_fetch_assoc($dr))) {
    if ($h['entrata'])
        $tot += $h['entrata'];
    else
        $tot -= $h['uscita'];
    $data = $h['data'];
    $phpdate = strtotime( $data );
    $mysqldate = date( 'd/m/Y', $phpdate );

    $desc = $h['descrizione'];
    if($h['entrata'])
        $entrata =  number_format($h['entrata'], 2, ',', '.');
    else
        $entrata = '';
    if($h['uscita'])
        $uscita = number_format($h['uscita'], 2, ',', '.');
    else
        $uscita = '';
    $tot2=number_format($tot, 2, ',', '.');
    $out[$i++] = "<tr><td style='text-align:center'>$mysqldate</td><td>$desc</td><td style='color:darkgreen;text-align:right'>$entrata</td><td style='color:red;text-align:right'>$uscita</td>

 <td style='text-align: right'>$tot2</td></tr>";
}

$str = '<table cellspacing="0" width="40%"><thead><th style=\'text-align:center\'>Data</th><th style=\'text-align:left\'>Descrizione</th><th style=\'text-align:right\'>Entrata</th><th style=\'text-align:right\'>Uscita</th><th style=\'text-align:right\'>Totale</th></thead></thead>';
for ($i = count($out) - 1; $i >= 0; $i--) {
    $str .= $out[$i];
}
$str .= '</table>';

echo $str;
?>
</tbody>
</body>
</html>
