<?php
require 'vendor/autoload.php';
include_once 'mp3file.class.php';

/* use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; */

$default_filename = 'result.xlsx'; // result filename
$limitBytes = 25000; // limit size 24kb
$limitDuration = 10; // limit 10 seconds

// deletes previous result
if (file_exists($default_filename)) {
  unlink($default_filename);
} 

$path = 'uploads/';
$files = array();
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveIteratorIterator::CHILD_FIRST), RecursiveIteratorIterator::CHILD_FIRST);

foreach($objects as $name => $object){
  if (!is_dir($name)) {

    $mp3file = new MP3File($name);
    $duration = $mp3file->getDurationEstimate(); // get duration
    $size = filesize($name); // get size

    array_push($files, array('name' => iconv("windows-1251", "UTF-8", $name), 'duration' => $duration, 'size' => $size));
  } 
    
}

/* $spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');
$writer = new Xlsx($spreadsheet);
$writer->save($default_filename); */





echo json_encode(array('allFiles' => print_r($files)));



