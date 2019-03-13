<?php
require 'vendor/autoload.php';
include_once 'getid3/getid3.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$default_filename = 'result.xlsx'; // result filename
$empty = 'uploads/empty/'; // path of empty folder
$limitBytes = 25000; // limit size 24kb
$limitDuration = 10; // limit 10 seconds

// deletes previous result
if (file_exists($default_filename)) {
  unlink($default_filename);
} 

$path = 'uploads/';
$files = array();

if (!is_dir($empty)) {       
  mkdir($empty, 0777, true);     
}  

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveIteratorIterator::CHILD_FIRST), RecursiveIteratorIterator::CHILD_FIRST);

foreach($objects as $name => $object){
  
  if (!is_dir($name)) {
    // Copy remote file locally to scan with getID3()
    $remotefilename = $name;
    if ($fp_remote = fopen($remotefilename, 'rb')) {
        $localtempfilename = tempnam('tmp/', 'getID3');
        if ($fp_local = fopen($localtempfilename, 'wb')) {
            while ($buffer = fread($fp_remote, 8192)) {
                fwrite($fp_local, $buffer);
            }
            fclose($fp_local);
            // Initialize getID3 engine
            $getID3 = new getID3;
            $ThisFileInfo = $getID3->analyze($localtempfilename);
            $duration = intval($ThisFileInfo['playtime_seconds']);
            $size = $ThisFileInfo['filesize'];

            // Delete temporary file
            unlink($localtempfilename);
        }
        fclose($fp_remote);
    }

    if ($duration < $limitDuration || $size < $limitBytes) {
      copy($name, $empty . pathinfo($name, PATHINFO_BASENAME));   
    }

    array_push($files, array('name' => iconv("windows-1251", "UTF-8", $name), 'duration' => $duration, 'size' => $size));
  }   
}

/* $spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');
$writer = new Xlsx($spreadsheet);
$writer->save($default_filename); */

echo json_encode(array('allFiles' => print_r($files)));
