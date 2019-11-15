<?php
session_start();

require_once("phpSpreadSheet/vendor/autoload.php");



$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("../data/BRMP.xls");
$sheet = $spreadsheet->getActiveSheet();

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('header');
$drawing->setDescription('header');
$drawing->setPath('../images/BRMP.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setWidth(1039);
$drawing->getShadow()->setVisible(true);
$drawing->getShadow()->setDirection(45);
$drawing->setWorksheet($sheet);

//fusion des cellules
/*$sheet->mergecells("A13:B13");
$sheet->mergecells("F13:G13");*/

//$sheet->mergecells("F7:H7");

//Inserer dans les cellules
$sheet->setCellValue('A10', $_SESSION['mag_src'][0]);
$sheet->setCellValue('C10', $_SESSION['mag_src'][1]);
$sheet->setCellValue('F10', $_SESSION['mag_src'][2]);
$sheet->setCellValue('H10', $_SESSION['chef_src'][1] . ' ' . $_SESSION['chef_src'][2]);

$sheet->setCellValue('F7', date("d/m/Y"));


//$sheet->setCellValue('A13', $_SESSION['mag_src_four'][1] . ' ' . $_SESSION['mag_src_four'][2]);



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





if(copy('../data/tmp.xlsx','../data/BRMP/' . md5(time()) . '.xlsx') && rename("../data/tmp.xlsx","../data/tmp.old"))
{
	header('location:../' . $_SESSION['referer'] . '&ok');
}
else
{
	header('location:../' . $_SESSION['referer'] . '&error');
}
	
