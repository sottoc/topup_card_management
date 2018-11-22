<?php
    require_once('api_common.php');
    $fileName = '';
    if(isset($_FILES["file"]["type"]))
    {
        $fileName = $_FILES["file"]["name"];
        $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
        $targetPath = $uploadPath.'/'.$fileName; // Target path where file is to be stored
        if (file_exists($uploadPath.'/'.$fileName)) {
            unlink($uploadPath.'/'.$fileName);
        }
        move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
        echo "Successfully uploaded!";
    }

    //read data from excel file
    

?>
