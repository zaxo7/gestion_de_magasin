<?php
session_start();

require_once("phpSpreadSheet/vendor/autoload.php");



$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("../data/BSMP.xls");
$sheet = $spreadsheet->getActiveSheet();

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('header');
$drawing->setDescription('header');
$drawing->setPath('../images/BSMP.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setWidth(1208);
$drawing->getShadow()->setVisible(true);
$drawing->getShadow()->setDirection(45);
$drawing->setWorksheet($sheet);

//fusion des cellules
$sheet->mergecells("A13:B13");
$sheet->mergecells("D13:E13");
$sheet->mergecells("F13:G13");


//Inserer dans les cellules
$sheet->setCellValue('A13', $_SESSION['mag_dst'][0]);
$sheet->setCellValue('C13', $_SESSION['mag_dst'][1]);
$sheet->setCellValue('F13', $_SESSION['mag_dst'][2]);
$sheet->setCellValue('H13', $_SESSION['chef_dst'][1] . ' ' . $_SESSION['chef_dst'][2]);


$total_HT = 0;

$line = 16;
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

$sheet->setCellValue('G36', $total_HT);






//this is how to write directly using loaded spreadsheet data
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->setPreCalculateFormulas(true);
$writer->save( "../data/tmp.xlsx" );

//print_r($_SESSION['stock_out']);

unset($_SESSION['stock_out']);
unset($_SESSION['mag_dst']);
unset($_SESSION['chef_dst']);
unset($_SESSION['mag_src']);
unset($_SESSION['chef_src']);





if(copy('../data/tmp.xlsx','../data/BSMP/' . md5(time()) . '.xlsx'))
{
	header('location:../' . $_SESSION['referer']);
}


?>