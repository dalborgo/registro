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


$dr = query("SELECT (SUM(entrata)-SUM(uscita)) as totale FROM `registro`;", $conn, true);
echo '€'.' '.number_format($dr["totale"],2,',','.');