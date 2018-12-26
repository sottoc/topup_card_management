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

        $len = strlen($fileName);
        $file_type = substr($fileName, $len-4, $len);
        if($file_type == '.xls'){
            
            //------------- get data from excel file ------------------
            include '../library/excel_reader/excel_reader.php';     // include the class
            $excel = new PhpExcelReader;
            $excel->setUTFEncoder('iconv');
            $excel->setOutputEncoding('UTF-8');
            $excel->read($targetPath);
            $nr_sheets = count($excel->sheets);       // gets the number of sheets
            $sheet = $excel->sheets[0];
            $rows = $sheet['numRows'];
            $cols = $sheet['numCols'];
            if($cols < 5){
                echo "Invalid content!";
                exit;
            }
            $total_number = 0;
            for($i=2;$i<=$rows;$i++){
                $user_code = $sheet['cells'][$i][1];
                $family_code = $sheet['cells'][$i][2];
                $card_id = $sheet['cells'][$i][3];
                $last_name = $sheet['cells'][$i][4];
                $first_name = $sheet['cells'][$i][5];
                $query = "SELECT * FROM tbl_card1 WHERE Card_ID = '".$card_id."'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    $warning = "Card number exist!";
                } else{
                    date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
                    $time = date("Y-m-d H:i:s");
                    $class = '';
                    $classification = 'Staff';
                    $username = $user_code;
                    $password = $user_code;
                    $level = "0";
                    $status = 'Active';
                    $image_name = "";
                    $query1 = "INSERT INTO `tbl_card1` (`User_code`, `Family_code`, `Card_ID`, `Last_name`, `First_name`, `Level`, `Class`, `Image`, `Card_status`, `Classification`, `Username`, `Password`, `Date_Created`, `Date_Updated`) VALUES ('".$user_code."','".$family_code."','".$card_id."','".$last_name."','".$first_name."','".$level."','".$class."','".$image_name."','".$status."','".$classification."','".$username."','".$password."','".$time."','".$time."')";
                    $result1 = $conn->query($query1);

                    $query2 = "SELECT * FROM tbl_family_code_amount WHERE family_code='".$family_code."'";
                    $result2 = $conn->query($query2);
                    if($result2->num_rows > 0){
                        $row2 = $result2->fetch_assoc();
                    } else{
                        $query3 = "INSERT INTO `tbl_family_code_amount` (family_code, amount, date_created, date_updated) VALUES ('".$family_code."', '0', '".$time."', '".$time."')";
                        $result3 = $conn->query($query3);
                    }

                    $total_number++;
                }
            }
            echo "Saved ".$total_number." cards";
        }

        if($file_type == 'xlsx'){
            //$xlsx = new SimpleXLSX($targetPath);
            //display_results($type);
            echo "Please select .xls file";
        }
        
    }

    //read data from excel file
    

?>
