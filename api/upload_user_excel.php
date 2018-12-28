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
                //echo strpos($sheet['cells'][1][1], "family_code");
                echo "Invalid content!";
                exit;
            }
            $total_number = 0;
            for($i=2;$i<=$rows;$i++){
                $family_code = $sheet['cells'][$i][1];
                $last_name = $sheet['cells'][$i][2];
                $first_name = $sheet['cells'][$i][3];
                $user_email = $sheet['cells'][$i][4];
                $password = $sheet['cells'][$i][5];
                $query = "SELECT * FROM tbl_user WHERE user_email = '".$user_email."'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    $warning = "email exist!";
                } else{
                    $query3 = "SELECT amount FROM tbl_user u LEFT JOIN tbl_family_code_amount fa ON u.family_code=fa.family_code WHERE u.family_code='".$family_code."'";
                    $result3 = $conn->query($query3);
                    if($result3->num_rows > 0){
                        $warning = "family code used!";
                    } else{
                        $email = $user_email;
                        $password = md5($password);
                        $type = "2";
                        $address = 'address';
                        $phone = '123456789';
                        $gender = '1';
                        $status = "1";
                        date_default_timezone_set('Asia/Singapore');//('Kuala Lumpur, Singapore');
                        $time = date("Y-m-d H:i:s");
                        $query="INSERT INTO `tbl_user` (`user_email`, `user_password`, `family_code`, `user_type_id`, `user_first_name`, `user_last_name`, `user_address`, `user_phone`, `user_gender_id`, `is_active`, `user_created_datetime`, `user_modified_datetime`) VALUES ('".$email."','".$password."','".$family_code."','".$type."','".$first_name."','".$last_name."','".$address."','".$phone."','".$gender."','".$status."','".$time."','".$time."')";
                        $result = $conn->query($query);

                        $query = "SELECT * FROM tbl_family_code_amount WHERE family_code='".$family_code."'";
                        $result = $conn->query($query);
                        if($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                        } else{
                            $query2 = "INSERT INTO `tbl_family_code_amount` (family_code, amount, date_created, date_updated) VALUES ('".$family_code."', '0', '".$time."', '".$time."')";
                            $result2 = $conn->query($query2);
                        }
                        $total_number++;
                    }
                }
            }
            echo "Saved ".$total_number." users";
        }

        if($file_type == 'xlsx'){
            //$xlsx = new SimpleXLSX($targetPath);
            //display_results($type);
            echo "Please select .xls file";
        }
        
    }

    //read data from excel file
    

?>
