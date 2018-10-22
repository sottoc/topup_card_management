<?php
    require_once('api_common.php');
    if(isset($_POST['user_code'])){
        $id = $_POST['id'];
        $user_code = $_POST['user_code'];
        $card_id = $_POST['card_id'];
        $level = $_POST['level'];
        $family_code = $_POST['family_code'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
    }
    $image_name = '';
    if(isset($_FILES["file"]["type"]))
    {
        $validextensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) 
            && ($_FILES["file"]["size"] < 200000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) 
        {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["file"]["error"];
            }
            else
            {
                $image_name = $card_id.'.'.$temporary[1];
                if (file_exists($uploadPath.'/'.$image_name)) {
                    unlink($uploadPath.'/'.$image_name);
                   // echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                }
               
                $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                $targetPath = $uploadPath.'/'.$image_name; // Target path where file is to be stored
                move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                //echo "Image Uploaded Successfully...!!";
                // echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
                // echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
                // echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                // echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";
            }
        }
        else
        {
            echo "***Invalid image Size or Type***  ";
        }
    }
    if($image_name == ''){
        $image_name = $_POST['image_name'];
    }
    $query = "UPDATE `tbl_card1` SET `User_code`='".$user_code."',`Family_code`='".$family_code."',`Card_ID`='".$card_id."',`Last_name`='".$last_name."',`First_name`='".$first_name."',`Level`='".$level."',`Image`='".$image_name."' WHERE `id`= '".$id."'";
    $result = $conn->query($query);
    echo "Successfully saved!";

?>
