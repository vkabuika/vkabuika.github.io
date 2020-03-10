<?php

require_once('phpmailer/PHPMailerAutoload.php');

$mail = new PHPMailer();

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'tls://smtp.gmail.com:587';             // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'example@gmail.com';      		  // SMTP username
$mail->Password = 'password';                      	  // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$message = "";
$status = "false";

$okMessage = 'RSVP form successfully submitted. Thank you, I will get back to you soon!';
$errorMessage = 'There was an error while submitting the form. Please try again later';

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_name'] != '' AND $_POST['form_option'] != '' AND $_POST['form_option2'] != '' AND $_POST['form_option3'] != '' AND $_POST['form_name2'] != '' AND $_POST['form_option4'] != '') {

        $name = $_POST['form_name'];
        $option = $_POST['form_option'];
        $option2 = $_POST['form_option2'];
        $option3 = $_POST['form_option3'];
		$name2 = $_POST['form_name2'];
        $option4 = $_POST['form_option4'];

        $subject = isset($subject) ? $subject : 'New Message | RSVP Form';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'example@gmail.com';                // Your Email Address
        $toname = 'Unlock Design';                     // Your Name

        if( $botcheck == '' ) {

            $mail->SetFrom( $email , $name );
            $mail->AddReplyTo( $email , $name );
            $mail->AddAddress( $toemail , $toname );
            $mail->Subject = $subject;

            $name = isset($name) ? "Name: $name<br><br>" : '';           
            $option = isset($option) ? "Option: $option<br><br>" : '';
            $option2 = isset($option2) ? "Option 2: $option2<br><br>" : '';
            $option3 = isset($option3) ? "Option 3: $option3<br><br>" : '';
			$name2 = isset($name2) ? "Guest Name: $name2<br><br>" : '';  
            $option4 = isset($option4) ? "Option 4: $option4<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = $name.' '.$option.' '.$option2.' '.$option3.' '.$name2.' '.$option4.' '.$referrer;

            $mail->MsgHTML( $body );
			$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			));
            $sendEmail = $mail->Send();

            if( $sendEmail == true ):
                $responseArray = array('type' => 'success', 'message' => $okMessage);
            else:
                $responseArray = array('type' => 'danger', 'message' => $errorMessage);
            endif;
        } else {
            $responseArray = array('type' => 'danger', 'message' => $errorMessage);
        }
    } else {
        $responseArray = array('type' => 'danger', 'message' => $errorMessage);
    }
} else {
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    
    header('Content-Type: application/json');
    
    echo $encoded;
}
else {
    echo $responseArray['message'];
}
?>