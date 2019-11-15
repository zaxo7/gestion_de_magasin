<?php
session_start();

require_once("phpSpreadSheet/vendor/autoload.php");



$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("../data/BTMP.xls");
$sheet = $spreadsheet->getActiveSheet();

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('header');
$drawing->setDescription('header');
$drawing->setPath('../images/BTMP.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setWidth(1057);
$drawing->getShadow()->setVisible(true);
$drawing->getShadow()->setDirection(45);
$drawing->setWorksheet($sheet);

//fusion des cellules
/*$sheet->mergecells("A13:B13");
$sheet->mergecells("D13:E13");
$sheet->mergecells("F13:G13");
*/
$sheet->mergecells("G37:H37");

//Inserer dans les cellules
$sheet->setCellValue('A11', $_SESSION['mag_src'][0]);
$sheet->setCellValue('C11', $_SESSION['mag_src'][1]);
$sheet->setCellValue('F11', $_SESSION['mag_src'][2]);
$sheet->setCellValue('H11', $_SESSION['chef_src'][1] . ' ' . $_SESSION['chef_src'][2]);


$sheet->setCellValue('A14', $_SESSION['mag_dst'][0]);
$sheet->setCellValue('C14', $_SESSION['mag_dst'][1]);
$sheet->setCellValue('F14', $_SESSION['mag_dst'][2]);
$sheet->setCellValue('H14', $_SESSION['chef_dst'][1] . ' ' . $_SESSION['chef_dst'][2]);



$sheet->setCellValue('H7', date("d/m/Y"));



$total_HT = 0;

$line = 17;
foreach ($_SESSION['stock_out'] as $art) {
	if($art[0] != 0)
	{
		$sheet->setCellValue('B' . "$line", $art[1]);
		$sheet->setCellValue('C' . "$line", $art[8]);
		$sheet->setCellValue('D' . "$line", $art[0]);
		$sheet->setCellValue('E' . "$line", $art[3]);
		$sheet->setCellValue('F' . "$line", $art[4]);
		$sheet->setCellValue('G' . "$line", $art[5]);
		$total_HT += $art[5];
	}
	$line++;
}

$sheet->setCellValue('G37', $total_HT);




//this is how to write directly using loaded spreadsheet data
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->setPreCalculateFormulas(true);
$writer->save( "../data/tmp.xlsx" );


unset($_SESSION['stock_out']);
unset($_SESSION['mag_dst']);
unset($_SESSION['chef_dst']);
unset($_SESSION['mag_src']);
unset($_SESSION['chef_src']);



if(copy('../data/tmp.xlsx','../data/BTMP/' . md5(time()) . '.xlsx') && rename("../data/tmp.xlsx","../data/tmp.old"))
{
	header('location:../' . $_SESSION['referer'] . '&ok');
}
else
{
	header('location:../' . $_SESSION['referer'] . '&error');
}

?>