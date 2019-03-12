<?php
    $ds = DIRECTORY_SEPARATOR;
    $storeFolder = 'uploads/';
   
   /*  
   **
   array_map('unlink', array_filter((array) glob("uploads/*"))); 
   */

    if (!empty($_FILES)) {
         
        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds . $storeFolder . $ds;  

        $fullPath = $storeFolder.rtrim($_POST['path'], "/.");
        $fullPath = iconv("UTF-8", "windows-1251", $fullPath);

       /* 
       **
        echo json_encode(array('$fullPath' => $fullPath, ));  
       */

        $folder = substr($fullPath, 0, strrpos($fullPath, "/"));

        if (!is_dir($folder)) {       
            mkdir($folder, 0777, true);     
        }    

        if (move_uploaded_file($tempFile, $fullPath)) {
            die($_FILES['file']['name']);
        } else {
            die('e');
        }
    }
?>
