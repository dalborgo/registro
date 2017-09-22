<?php
$arri[0] = '2020-01-01';
$arri[1] = '2000-01-01';
$rr = 0;
foreach ($_GET as $lor) {
    $arri[$rr++] = $lor;
}
//print_r($arri);
$file = 'report/report.csv';


?>


<html>
<head>
    <title>Storico Cassa</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <style>

        body {
            font-family: sans-serif;
        }

        table {
            border: 1px solid gray;
            font-size: 11pt
        }

        tbody tr:nth-child(even) {
            background-color: #cbdbef;
        }

        td {
            padding: 2px 5px;
        }

        thead {
            background-color: #1969e6;
            color: white;
        }

        th {
            padding: 5px;
        }
    </style>
    <script>
        $(function () {
            $.datepicker.regional['it'] = {
                closeText: 'Chiudi',
                prevText: '&#x3c;Prec',
                nextText: 'Succ&#x3e;',
                currentText: 'Oggi',
                monthNames: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                    'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
                monthNamesShort: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu',
                    'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
                dayNames: ['Domenica', 'Luned&#236', 'Marted&#236', 'Mercoled&#236', 'Gioved&#236', 'Venerd&#236', 'Sabato'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
                dayNamesMin: ['Do', 'Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['it']);


            $("#from").datepicker({
                changeMonth: true,
                dateFormat: "dd/mm/yy",
                onClose: function (selectedDate) {
                    $("#to").datepicker("option", "minDate", selectedDate);
                },
                onSelect: function () {
                    var fine = $('#to').val();
                    var iniz = $('#from').val();
                    if (prova(fine) && prova(iniz))
                        $('#filtra').prop('disabled', false);
                    else
                        $('#filtra').prop('disabled', true);
                }
            });
            $("#to").datepicker({
                changeMonth: true,
                dateFormat: "dd/mm/yy",

                onClose: function (selectedDate) {
                    $("#from").datepicker("option", "maxDate", selectedDate);
                },
                onSelect: function () {
                    var fine = $('#to').val();
                    var iniz = $('#from').val();
                    if (prova(fine) && prova(iniz))
                        $('#filtra').prop('disabled', false);
                    else
                        $('#filtra').prop('disabled', true);
                }
            });
        });
        function filtra() {
            var fine = $('#to').val();
            var iniz = $('#from').val();
            if (prova(fine) && prova(iniz)) {
                var newini = iniz.split("/").reverse().join("-");
                var newfine = fine.split("/").reverse().join("-");
                if (newini <= newfine)
                    location.href = 'report2.php?datafine=' + newfine + '&datainzio=' + newini;
            }
        }
        function azzera_filtri() {
            location.href = 'report2.php';
        }
        function prova(input) {
            var reg = /(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d/;
            if (input.match(reg))
                return true;
            else
                return false;
        }
    </script>
</head>


<body>
<div style="float:left; padding: 20px 10px">

    <label for="from">Da</label>
    <input type="text" id="from" name="from" style="width:110px">
    <label for="to">a</label>
    <input type="text" id="to" name="to" style="width:110px">
    <input type="button" id="filtra" onclick="filtra()" value="Filtra" style="margin-right: 0px" disabled="disabled">
    <input type="button" id="azz_filtri" onclick="azzera_filtri()" value="Azzera" style="margin-right: 20px">
    <input type="button" onclick="window.open('<?= $file ?>');" value="Esporta CSV">
</div>
<div style="clear: both"></div>
<?php
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


$dr = query("set @csum := 0.00;", $conn);
$dr = query("set @usum := 0.00;", $conn);
$dr = query("select data, entrata, uscita, descrizione, (@csum := @csum + ifNULL(entrata,0)) as cumulative_sum, (@usum := @usum + IFNULL(uscita,0)) as cumulative_usum, TRUNCATE(@csum - @usum,2) as totale from registro WHERE CAST(data AS DATE) >= '$arri[1]' AND CAST(data AS DATE) <='$arri[0]' order by data;", $conn);

$out = '';
$csv2 = "Data;Descrizione;Entrata;Uscita;Totale;\n";
$csv = '';

while (($h = mysqli_fetch_assoc($dr))) {
    $data = $h['data'];
    $phpdate = strtotime($data);
    $mysqldate = date('d/m/Y', $phpdate);
    $desc = $h['descrizione'];
    if ($h['entrata'])
        $entrata = number_format($h['entrata'], 2, ',', '.');
    else
        $entrata = '';
    if ($h['uscita'])
        $uscita = number_format($h['uscita'], 2, ',', '.');
    else
        $uscita = '';
    $tot2 = number_format($h['totale'], 2, ',', '.');
    $out = "<tr><td style='text-align:center'>$mysqldate</td><td>$desc</td><td style='color:darkgreen;text-align:right'>$entrata</td><td style='color:red;text-align:right'>$uscita</td>
<td style='text-align: right'>$tot2</td></tr>" . $out;
    $csv = "$mysqldate;$desc;$entrata;$uscita;$tot2;\n" . $csv;
}

$str = '<table cellspacing="0" width="40%"><thead><th style=\'text-align:center\'>Data</th><th style=\'text-align:left\'>Descrizione</th><th style=\'text-align:right\'>Entrata</th><th style=\'text-align:right\'>Uscita</th><th style=\'text-align:right\'>Totale</th></thead></thead>';
$str .= $out;
$str .= '</table>';
echo $str;
file_put_contents($file, $csv2 . $csv);
?>

</body>
</html>
