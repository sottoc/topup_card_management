<?php
require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";
require_once "PHPMailer/src/Exception.php";
require_once "config.php";

class CSendMail
{
    private $mail, $mailer, $body;

    function __construct( $mail_address, $body)
    {        
        $this->mail = $mail_address;
        $this->mailer = new \PHPMailer\PHPMailer\PHPMailer();
        $this->body = $body;
    }

    public function sendMail(){
        $email = $this->mailer;
        $email->isSMTP();
        // $email->Host = 'relay-hosting.secureserver.net';
        // $email->Port = 25;
        // $email->SMTPAuth = false;
        // $email->SMTPSecure = false;
        $email->From = "Chartwell-Lycee-Support@gmail.com";
        $email->FromName  = "Chartwell-Lycee-Support";
        //$email->SetFrom($address, $address);
        //$email->IsSMTP(); // enable SMTP
        //$email->SMTPDebug = 4;  // debugging: 1 = errors and messages, 2 = messages only
        $email->SMTPAuth = true;  // authentication enabled
        $email->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        
        $email->Host = 'smtp.gmail.com';
        $email->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $email->IsHtml(true);
        $email->Port = 465;
        $email->Username = GUSER;
        $email->Password = GPWD;
        $email->Subject   = MAIL_SUBJET;
        $email->Body      =  $this->body;
        $email->AddAddress( $this->mail );     


        // $result = $email->Send();
        // echo print_r($result);
        if (!$email->Send()) {
            // throw new Exception($email->ErrorInfo);
            var_dump($email->ErrorInfo);
        }
        // $cur_arr = array("email"=>$address, "result"=>$result);
        // echo $address;
        // exit();
        // array_push($this->return_value, $cur_arr);
    }

    public function sendMailPassword(){
       
        $this->sendMail();
        
    }

    function __destruct()
    {
        // echo json_encode($this->return_value);
        // $this->return_value = array();
    }
}

